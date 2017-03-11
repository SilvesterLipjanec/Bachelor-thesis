/*
 * Soubor:  proj3.c
 * Datum:   7.12.2014
 * Autor:   Silvester Lipjanec, xlipja01@stud.fit.vutbr.cz
 * Projekt: Prechod bludiskom, projekt c. 3 pre predmet IZP
 * Popis:   Program, který v daném bludišti a jeho vstupu najde průchod ven.
            Bludiště je uloženo v textovém souboru ve formě obdélníkové matice
            celých čísel. Prodram výpisuje souřadnice políček bludiště,
            přes které vede cesta z vchodu bludiště do jeho východu.

 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <stdbool.h>
#include <math.h>

#define THRESHOLD 150
#define CONSTH 35
#define CONSTW 35
#define PI 3.14159265 
#define OFFCONST 400 


/** Kody chyb programu */
enum tecodes
{
  EOK = 0,     /**< Bez chyby */
  ECLWRONG,    /**< Chybny prikazovy riadok*/
  EOPEN, 		/**< Chyba pri otvarani suboru */
  EFORMAT, 		/**< Chybny fomat suboru*/
  EREAD,		/**< Chyba pri citani suboru*/
  EALLOC, 		/**< Chyba pri alokacii pamate*/
  EUNKNOWN      /**< Neznama chyba */
};

/** Stavove kody programu */
enum tstates
{
  SHELP,         /**< Napoveda */
  SORDINAL,      /**< Poradie dane ordinalnymi hodnotami. */
  SANALYZE
};


/** Definovanie struktury typu PPMPixel pre pixel obsahujuci RGB zlozky */
typedef struct 
{	
	unsigned char red, green, blue;
}PPMPixel;


/** Definovanie struktury typu PPMImage pre obrazok typu PPM */
typedef struct 
{	
	int width;
	int height;
	int maxVal;
	PPMPixel **data;	
}PPMImage;

/** Definovanie štruktúry typu Point definujúcu súradnice bodu */
typedef struct 
{
	int r;
	int c;
}Point;

/** Definovanie štruktúry typu Lines obsahúcu dva body */
typedef struct
{
	Point fst;
	Point lst;	
}Lines;

/** Definovanie štruktúry, ktorá uchováva info. o pozícii v strede liny 
a pozícii nasledujúcej bielej oblasti v jednom smere */
typedef struct
{
  int mid;
  int nextWhite;
} MidNextInfo;



/** Chybová hlasenia odpovedajuce chybovym kodom. */
const char *ECODEMSG[] =
{
  [EOK] = "Vsetko v poriadku.\n",
  [ECLWRONG] = "Chybne parametre prikazoveho riadku! (Prepinac --help pre zobrazenie napovedy)\n",
  [EOPEN] = "Chyba pri otvarani suboru!\n",
  [EFORMAT] = "Chybny format suboru! Potrebny format P6!\n",
  [EUNKNOWN] = "Nastala nepredvidana chyba!\n",
  [EALLOC] = "Chyba pri alokacii pamate!\n",
  [EREAD] = "Nastala chyba pri citani suboru!\n"
};
const char *HELPMSG =
        "Program pre analyzu PPM suborov.\n"
        "Autor: Silvester Lipjanec. \n"
        "analyze --help :sposobi, ze program vypise napovedu a skonci.\n"
        "analyze [TARGET] :analyzuje subor formatu PPM zadany ako TARGET\n";


/**
 * Struktura obsahujuca hodnoty parametrov prikazoveho riadku.
 */
typedef struct params
{
  char *filename;    /**< Nazov suboru, ktory ma byt analyzovany*/
  int ecode;         /**< Chybovy kod programu, odpoveda tecodes. */
  int state;         /**< Stavovy kod programu, odpoveda tstates. */
} TParams;

/**
 * Vypise hlasenie odpovedajuce chybovemu kodu
 * @param ecode kod chyby programu
 */
void printECode(int ecode)
{
  if (ecode < EOK || ecode > EUNKNOWN)
  { ecode = EUNKNOWN; }

  fprintf(stderr, "%s", ECODEMSG[ecode]);
}
/**
 * Ukladá obrázok do pamäte ako 2D pole
 * @param *filename Názov obrázka, ktorý sa ukladá 
 * @return Vracia ukazateľ na štruktúru obrázka
 */
static PPMImage *read_data(char * filename)
{
	PPMImage *img;
	FILE *file;

	file = fopen(filename, "rb");
	if(file == NULL)
	{
		printECode(EOPEN);
		exit(1);
	}
	//nacitanie formatu suboru
	char buff[3];
	if (!fgets(buff, sizeof(buff), file)) {
	      perror(filename);
	      exit(1);
	 }
	 
    //kontrola formatu suboru
    if (buff[0] != 'P' || buff[1] != '6') {
         printECode(EFORMAT);
         exit(1);
    }
   	//alokacia pamate pre strukturu PPMImage
    if((img = (PPMImage *)malloc(sizeof(PPMImage))) == NULL){
    	printECode(EALLOC);
    	exit(2);
    }

    //nacitanie vysky, sirky obrazka a max.hodnoty RBG zloziek
    fscanf(file,"%d %d %d" , &img->width, &img->height, &img->maxVal);
	
    //alokacia pamate pre ukazatele na data v strukture
	if((img->data = (PPMPixel **) malloc(img->height * (sizeof(PPMPixel*)))) == NULL)
	{
		printECode(EALLOC);
    	exit(2);
	}
	
	//alokacia pamate pre data typu PPMPixel
	for(int i = 0 ; i < img->height ; i++)
	{	
		if((img->data[i] = (PPMPixel *) malloc(img->width * (sizeof(PPMPixel)))) == NULL)
		{
			printECode(EALLOC);
    		exit(2);
		}
	}

	while(fgetc(file) != '\n');
	//
	for(int i = 0 ; i < img->height ; i++)
	{
		if(fread(img->data[i], 3, img->width,file) != img->width)
		{
			printECode(EREAD);
			exit(1);
		}		
	}

	fclose(file);	

	return img;
}

void writePPM(const char *filename, PPMImage *img, Lines line, int dist)
{/*
	img->data[line.fst.r][line.fst.c].red = 255;
	img->data[line.fst.r][line.fst.c].green = 0;
	img->data[line.fst.r][line.fst.c].blue = 0;
	img->data[line.lst.r][line.lst.c].red = 255;
	img->data[line.lst.r][line.lst.c].green = 0;
	img->data[line.lst.r][line.lst.c].blue = 0;*/
	/*for(int i = 0 ; i < 20 ; i++)
	{
		img->data[line.fst.r][line.fst.c].red = 255;
		img->data[line.fst.r][line.fst.c].green = 0;
		img->data[line.fst.r][line.fst.c].blue = 0;
		line.fst.c += dist;
	}*/
    FILE *fp;
    //open file for output
    fp = fopen(filename, "wb");
    if (!fp) {
         printECode(EOPEN);
         exit(1);
    }

    //write the header file
    //image format
    fprintf(fp, "P6\n");


    //image size
    fprintf(fp, "%d %d\n",img->width,img->height);

    // rgb component depth
    fprintf(fp, "%d\n",img->maxVal);

    // pixel data
    for(int r = 0 ; r < img->height ; r++)
    {
    	fwrite(img->data[r], 3, img->width, fp);
    
    }
    fclose(fp);
}
/**
 * Porovnava bod obrazku s threshold hodnotou
 * @param img Obrázok
 * @param r Súradnica y
 * @param c Súradnica x
 * @return Vracia true ak je hod. fareb. zloziek mensia ako threshold - cierna farba ? true
 */
bool isBlack(PPMImage*img, int r, int c)
{
	if( img->data[r][c].red < THRESHOLD && 
 		img->data[r][c].green < THRESHOLD &&
 	    img->data[r][c].blue < THRESHOLD )
	    return true;
	else return false;
}
void writePoint(PPMImage*img, int r, int c)
{
	img->data[r][c].red = 255;
	img->data[r][c].green = 0;
	img->data[r][c].blue = 0;
}
void writePointVal(PPMImage*img, int r, int c,int red,int green,int blue)
{
	img->data[r][c].red = red;
	img->data[r][c].green = green;
	img->data[r][c].blue = blue;
}

void printPoint(PPMImage*img, int r, int c)
{
	printf("R: %d G: %d B: %d\n",img->data[r][c].red,img->data[r][c].green ,img->data[r][c].blue);

}

/**
 * Hľadá vrchol počiatočnej a koncovej vrchnej vertikálnej pomocnej čiary
 * @param img Obrázok, v ktorom sa hľadajú pomocné čiary
 * @return Vracia šturktúru obsahujúcu body prvej a poslednej vertikálnej čiary
 */
Lines findVertPts(PPMImage *img)
{
	Point fstLine;
	Point lstLine;
	int br = 0; 
	int SC = img->width / CONSTW; //SC = start column
	int SR = img->height / CONSTH; //SR = start row
	int ER = img->height / 1.7; //ER = end row
	int EC = img->width - SC; //EC = end column
	
	//find point of first vertical line 	
	fstLine.r = SR;
	fstLine.c = EC;
    for(int r = SR ; r < ER ; r++){         	
     	for(int c = SC ; c <  EC ; c++){
	        if( isBlack(img,r,c) )
	         	{
	         		if(c < fstLine.c)
	         		{ 
	         			if((fstLine.c - c) <= 2)
						{
							br = 1;							
						}
						else
						{ 
		         			fstLine.c = c;
			         		fstLine.r = r;
						}
					}
					else br = 1;
					break;	          		
	          	}
     	}
     	if(br) break;
    }
    br = 0;
    //find point of last vertical line  
    lstLine.r = SR;
	lstLine.c = SC;	
	
    for(int r = SR ; r < ER ; r++){         	
     	for(int c = EC ; c > SC ; c--){
	        if( isBlack(img,r,c) )
	         	{
	         		if(c > lstLine.c)
	         		{ 
	         			if((c - lstLine.c) <= 2)
						{
							br = 1;							
						}
						else
						{ 
		         			lstLine.c = c;
			         		lstLine.r = r;
						}
					}
					else br = 1;
					break;	          		
	          	}
	     
     	}
     	if(br) break;
     }
    

	Lines line = {
		.fst = fstLine,
		.lst = lstLine
	};
	return line;
}
/**
 * Hľadá uhol potrebný k vyrovaniu obrázku
 * @param line Struktura obsahujuca suradnice pociatocneho a koncoveho bodu
 * @return Vracia uhol, o ktory je nutne obrazok otocit k vyrovnaniu.
 */
double findAngle(Lines line)
{
	double angle = 0.0;
	int a = 0;
	int b = 0;
	double ratio = 0.0;
	double val = 180.0 / PI;
	unsigned char i = 0;
	/* ._____
			 _____.	*/

	if(line.fst.r < line.lst.r)
	{
		i = 1;
		a = line.lst.r - line.fst.r;		
	}
	/*			_____.
		._______	     */
	else
	{
		a = line.fst.r - line.lst.r;
	}
	b = line.lst.c - line.fst.c;
	ratio = (double)a / (double)b;
	angle = atan(ratio) * val;

	return i ? angle : -angle;
}



/**
 * Spracuje argumenty príkazového riadka a vráti ich v štruktúre TParams.
 * Ak sú zadané chybné parameter vráti štruktúru s chybovým kódom.
 * @param argc Počet argumentov.
 * @param argv Pole textových reťazcov argumentov.
 * @return Vrací analyzované argumenty příkazového řádku.
 */
TParams getParams(int argc, char *argv[])
{
  TParams result =
  { // inicializace struktury
  	
    .ecode = EOK,
    .state = SORDINAL,
  };

  if(argc == 2)
  {
    if(strcmp("--help", argv[1]) == 0) // tisk nápovìdy
	{
		result.state = SHELP;
	}
	else
	{
		result.filename = argv[1];
		result.state = SANALYZE;
	}
  }
  else
  { // nespravny pocet parametrov
    result.ecode = ECLWRONG;
  }

  return result;
}


void deallocMem(PPMImage *img)
{
	//alokacia pamate pre data typu PPMPixel
	for(int i = 0 ; i < img->height ; i++)
	{	
		free(img->data[i]);
		
	}
	free(img->data);
	free(img);	
}
void deallocIndexes(int *vert, int *horiz)
{
	free(vert);
	free(horiz);
}

/**
 * Funkcia na vyhladanie cisla riadku v strede liny vo vertikalnom smere 
 * @param r Riadok vrcholu liny
 * @param c Stlpec vrcholu liny
 * @return Vracia riadok v strede liny
 */

MidNextInfo findHorizInfo(PPMImage *img, int r, int c)
{
	MidNextInfo inf = {
		.mid = 0,
		.nextWhite = 0
	};
	int col = c;
	while(isBlack(img,r,col))
	{
		col++;		
	}
	inf.nextWhite = col;
	inf.mid = c + ((col - c - 1) / 2);
	return inf;
}
MidNextInfo findVertInfo(PPMImage *img, int r, int c)
{
	MidNextInfo inf = {
		.mid = 0,
		.nextWhite = 0
	};
	int row = r;
	while(isBlack(img,row,c))
	{
		row++;		
	}
	inf.nextWhite = row;
	inf.mid = r + ((row - r - 1) / 2);
	return inf;
}
int findNextHoriz(PPMImage *img, int r, int c)
{	
	while(!isBlack(img,r,c))
	{
		c++;
	}
	return c;
}
int findNextVert(PPMImage *img, int r, int c)
{	
	while(!isBlack(img,r,c))
	{
		r++;
	}
	return r;
}

int *findVertLines(PPMImage *img, Lines line, Point *StHorPt, int *Ccnt)
{
	MidNextInfo Hinf;	
	MidNextInfo Vinf;
	Point actV = line.fst;		
	int *vert;

	if((vert = (int *)malloc(sizeof(int*))) == NULL)
	{
		printECode(EALLOC);
    	exit(2);
	}
	//posun act.r na stred vertikalnej liny
	Vinf = findVertInfo(img , actV.r , actV.c);
	actV.r = Vinf.mid;
	StHorPt->r = Vinf.nextWhite - 1;
	StHorPt->c = actV.c;
	//hladaj dalsiu vertikalnu linu v smere x
 	while(actV.c < line.lst.c)
	{
		*Ccnt += 1;
		if((vert = (int *)realloc(vert,*Ccnt*sizeof(int *))) == NULL)
		{
			printECode(EALLOC);
    		exit(2);
		}
		Hinf = findHorizInfo(img, actV.r, actV.c);
		actV.c = Hinf.nextWhite;
		vert[*Ccnt-1] = Hinf.mid;
		writePoint(img, actV.r, Hinf.mid);
		actV.c = findNextHoriz(img, actV.r, actV.c );
	}
	//Hladaj pociatocny bod prvej horizontalnej liny
	while(isBlack(img,StHorPt->r,StHorPt->c))
	{
		while(isBlack(img,StHorPt->r,StHorPt->c ))
		{
			StHorPt->c--;
		}
		StHorPt->c++;
		StHorPt->r--;
	}
	StHorPt->r++;
	
	return vert;
}
int *findHorizLines(PPMImage*img, Point actH, int *Rcnt)
{
	MidNextInfo Hinf;	
	MidNextInfo Vinf;
	int ER = img->height / 1.7; //ER = end row
	int *horiz;

	if((horiz = (int *)malloc(sizeof(int*))) == NULL)
	{
		printECode(EALLOC);
    	exit(2);
	}

	Hinf = findHorizInfo(img, actH.r, actH.c);
	actH.c = Hinf.mid;
	//hladaj dalsiu horizontalnu linu v smere y
	while(actH.r < ER)
	{
		*Rcnt += 1;
		if((horiz = (int *)realloc(horiz,*Rcnt*sizeof(int *))) == NULL)
		{
			printECode(EALLOC);
    		exit(2);
		}
		Vinf = findVertInfo(img, actH.r, actH.c);
		actH.r = Vinf.nextWhite;
		horiz[*Rcnt-1] = Vinf.mid;
		writePoint(img,Vinf.mid, actH.c);
		actH.r = findNextVert(img,actH.r,actH.c );
	}
	
	return horiz;
}

PPMPixel findPixelVal(PPMImage* img,int* vertIndex,int* horizIndex, int idR, int idC)
{
	
	float fOFFSET = (float)img->width / (float)OFFCONST;
	int OFFSET = round(fOFFSET);
	int SR = horizIndex[idR] + OFFSET;
	int ER = horizIndex[idR+1] - OFFSET;
	int SC = vertIndex[idC] + OFFSET;
	int EC = vertIndex[idC+1] - OFFSET;
	int red = 0, green = 0, blue = 0;
	
	PPMPixel px = {
		.red = 0,
		.green = 0,
		.blue = 0
	};
	int cnt = 0;
	for(int r = SR ; r < ER ; r++)
	{
		for(int c = SC ; c < EC ; c++)
		{

			cnt++;			
			red += img->data[r][c].red;
			green += img->data[r][c].green;
			blue += img->data[r][c].blue;
		}
	}
	
	px.red = round((float)red / (float)cnt);
	px.green = round((float)green / (float)cnt);
	px.blue = round((float)blue / (float)cnt);
	
	for(int r = SR ; r < ER ; r++)
	{
		for(int c = SC ; c < EC ; c++)
		{
			
			writePointVal(img,r,c,px.red,px.green,px.blue);
			
		}
	}
	return px;
}



/////////////////////////////////////////////////////////////////
/**
 * Hlavní program.
 */

int main(int argc, char *argv[])
{
    
    TParams params = getParams(argc, argv);

    if (params.ecode != EOK)    //chyba v parametroch
	{
	    printECode(params.ecode);
	    return EXIT_FAILURE;
	}
    else if (params.state == SHELP)
    {
        printf("%s", HELPMSG);
        return EXIT_SUCCESS;
    }	
    else if(params.state == SANALYZE)
    {
    	printf("Analyze of %s\n",params.filename );
    	
    	PPMImage *image;
    	Lines line;
    	double angle;
    	char rotate_cmd[255]; 
    	char alg_filename[255];
    	Point StHorPt;
    	int *vertIndex;
    	int *horizIndex;
    	int Rcnt = 0;
    	int Ccnt = 0;
    	image = read_data(params.filename);
    	line = findVertPts(image);

    	//ak farebny vzor nieje rovno
    	printf("%d %d \n",line.fst.r, line.fst.c );
    	printf("%d %d \n",line.lst.r, line.lst.c );


    	if(line.fst.r != line.lst.r)
    	{

    		sprintf(alg_filename,"aligned_%s",params.filename);
    		printf("%s\n",alg_filename );
    		//najdi uhol, ktory je potrebny na vyrovnanie obrazka
    		angle = findAngle(line);
    		sprintf(rotate_cmd,"pnmrotate %lf %s > %s",angle,params.filename,alg_filename);
    		//rotuj obrazok a uloz ho ako aligned_{image name}
    		system(rotate_cmd);
    		printf("%s\n",alg_filename );
    		//uvolni pamat predchadzajuceho obrazka
    		deallocMem(image);
		
    		image = read_data(alg_filename);
    		line = findVertPts(image);
    		
    		  	
    	}
		printf("vyrovnany\n");
    	printf("%d %d \n",line.fst.r, line.fst.c );
    	printf("%d %d \n",line.lst.r, line.lst.c ); 
    	vertIndex = findVertLines(image, line, &StHorPt, &Ccnt);    	
    	horizIndex = findHorizLines(image, StHorPt, &Rcnt);

 		
 		PPMPixel area[Ccnt - 3][Rcnt - 3];
 		area[0][0].red = 255;
 		area[0][0].green = 255;
 		area[0][0].blue = 255;
 		FILE *file;
 		float priemerR;
 		float priemerG;
 		float priemerB;

 		int sucetR = 0;
 		int sucetG = 0;
 		int sucetB = 0;
 		int pocet = 0;
		file = fopen("/var/www/html/BP/analyze/vysl.txt", "wb");
 		for(int r = 1 ; r <= Rcnt - 3 ; r++)
 		{
 			for(int c = 1 ; c <= Ccnt - 3 ; c++)
 			{	
 				pocet ++;
				area[r][c] = findPixelVal(image, vertIndex,horizIndex, r,c);
 				fprintf(file,"%d %d %d\n", area[r][c].red,area[r][c].green,area[r][c].blue);
 				sucetR = sucetR + area[r][c].red;
 				sucetG = sucetG + area[r][c].green;
 				sucetB = sucetB + area[r][c].blue;
 			}
 		}
 		priemerR = sucetR /pocet;
 		priemerG = sucetG / pocet;
 		priemerB = sucetB / pocet;
 		fprintf(file, "%f\n",priemerR );
 		fprintf(file, "%f\n",priemerG );

 		fprintf(file, "%f\n",priemerB );

 		fclose(file);
    	writePPM("analyzedImage.ppm",image,line,1);
    	deallocMem(image);
    	deallocIndexes(vertIndex,horizIndex);
 
		
    }
   
  return EXIT_SUCCESS;
}

/* konec BP */
