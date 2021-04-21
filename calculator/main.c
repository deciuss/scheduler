#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <stdbool.h>
#include "Params.c"
#include "evolution.c"

void printPopulation(int populationCardinality, int numberOfEvents, int population[populationCardinality][numberOfEvents][2]) {
    for (int i = 0; i < populationCardinality; i++) {
        for (int j = 0; j < numberOfEvents; j++) {
            printf("(%d,%d) ", population[i][j][0], population[i][j][1]);
        }
        printf("\n");
    }
}

int getIntFromFileLine(FILE *fp) {
    char buff[255];
    fgets(buff, 255, (FILE*)fp);
    return atoi(buff);
}

void populateBoolMatrixWithFileLine(FILE *fp, int sizeX, int sizeY, bool matrix[sizeX][sizeY]) {
    for (int i = 0; i < sizeX; i++) {
        for (int j = 0; j < sizeY; j++) {
            matrix[i][j] = (fgetc(fp) == '0') ? false : true;
        }
    }
    if (fgetc(fp) != '\n') exit(21);

}

void populateIntArrayWithFileLine(FILE *fp, int size, int arr[size]) {
    for (int i = 0; i < size; i++) {
        arr[i] = fgetc(fp) - '0';
    }
    if (fgetc(fp) != '\n') exit(22);
}

int main(int argc, char * argv[]) {

    srand(time(0));

    FILE *fp;
    fp = fopen("../../var/calculator/calculator_file", "r");

    struct Params p;
    p.numberOfEvents = getIntFromFileLine(fp);
    p.numberOfRooms = getIntFromFileLine(fp);
    p.numberOfTimeslots = getIntFromFileLine(fp);
    p.numberOfSurvivors = 4;
    p.populationCardinality = 30;
    p.hardViolationFactor = 1;

    bool eventTimeslotShare[p.numberOfEvents][p.numberOfEvents];
    bool eventRoomFit[p.numberOfEvents][p.numberOfRooms];
    bool eventSameSubject[p.numberOfEvents][p.numberOfEvents];
    int eventBlockSize[p.numberOfEvents];
    bool timeslotNeighborhood[p.numberOfTimeslots][p.numberOfTimeslots];

    populateBoolMatrixWithFileLine(fp, p.numberOfEvents, p.numberOfEvents, eventTimeslotShare);
    populateBoolMatrixWithFileLine(fp, p.numberOfEvents, p.numberOfRooms, eventRoomFit);
    populateBoolMatrixWithFileLine(fp, p.numberOfEvents, p.numberOfEvents, eventSameSubject);
    populateIntArrayWithFileLine(fp, p.numberOfEvents, eventBlockSize);
    populateBoolMatrixWithFileLine(fp, p.numberOfTimeslots, p.numberOfTimeslots, timeslotNeighborhood);

    fclose(fp);

    doEvolution(p, eventTimeslotShare, eventRoomFit, 10000000);



//    int populationSize = 1;
//
//    int bestIndividualViolation = 999999999;
//
//    for (int k = 0; k < 999999; k++) {
//        int population[populationSize][p.numberOfEvents][2];
//        initializePopulation(populationSize, p.numberOfEvents, p.numberOfTimeslots, p.numberOfRooms, population, eventRoomFit);
//
//        for (int i = 0; i < populationSize; i++) {
////        printf("Population %i hard violation: %d\n", i, calculateHardViolation(p.numberOfEvents, p.numberOfRooms, 1, population[i], eventTimeslotShare, eventRoomFit));
//            int violation = calculateHardViolation(p.numberOfEvents, p.numberOfRooms, 1, population[i], eventTimeslotShare);
//            if (violation < bestIndividualViolation) {
//                bestIndividualViolation = violation;
//                printf("Attempt %d individual %d violation: %d\n", k, i, bestIndividualViolation);
//            }
//        }
//
//
//    }






    return 0;
}




