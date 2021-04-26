#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <stdbool.h>
#include "Params.c"
#include "utils.c"
#include "Node.c"
#include "evolution.c"

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

void flatTimeslotNeighborhoodMatrix(
        struct Params p,
        bool timeslotNeighborhood[p.numberOfTimeslots][p.numberOfTimeslots],
        int timeslotNeighborhoodFlat[p.numberOfTimeslots][2]
) {
   for (int i = 0; i < p.numberOfTimeslots; i++) {
       timeslotNeighborhoodFlat[i][0] = -1;
       timeslotNeighborhoodFlat[i][1] = -1;
       for (int j = 0; j < p.numberOfTimeslots; j++) {
            if (i == j) continue;
            if (timeslotNeighborhood[i][j] == true) {
                if (i > j) timeslotNeighborhoodFlat[i][0] = j;
                if (i < j) timeslotNeighborhoodFlat[i][1] = j;
            }
       }
   }
}

void populateBlockArray(FILE *fp, int size, struct Node *eventBlock[size]) {
    for (int i = 0; i < size; i++) {
        eventBlock[i] = NULL;
        int blockSize = getIntFromFileLine(fp);
        for (int j = 0; j < blockSize; j++) {
            eventBlock[i] = *Node_addOrCreate(&eventBlock[i], getIntFromFileLine(fp));
        }
    }
}

int main(int argc, char * argv[]) {

    srand(time(0));

    FILE *fp;
    fp = fopen("../../var/calculator/calculator_file", "r");

    struct Params p;
    p.numberOfEvents = getIntFromFileLine(fp);
    p.numberOfRooms = getIntFromFileLine(fp);
    p.numberOfTimeslots = getIntFromFileLine(fp);
    p.numberOfSurvivors = 2;
    p.maxBlockSize = 9;
//    p.hardViolationFactor = p.numberOfEvents * p.maxBlockSize + 1;
    p.hardViolationFactor = 1;
    p.mutation1Rate = 0;
    p.mutation2Rate = 1;
    p.mutation3Rate = 0;
    p.populationCardinality = 100;
    p.broodSplit[0][0] = 0;
    p.broodSplit[0][1] = p.populationCardinality / 2 - 1;
    p.broodSplit[1][0] = p.populationCardinality / 2;
    p.broodSplit[1][1] = p.populationCardinality;

    bool eventTimeslotShare[p.numberOfEvents][p.numberOfEvents];
    bool eventRoomFit[p.numberOfEvents][p.numberOfRooms];
    bool eventSameSubject[p.numberOfEvents][p.numberOfEvents];
    int eventBlockSize[p.numberOfEvents];
    bool timeslotNeighborhood[p.numberOfTimeslots][p.numberOfTimeslots];
    int timeslotNeighborhoodFlat[p.numberOfTimeslots][2];


    populateBoolMatrixWithFileLine(fp, p.numberOfEvents, p.numberOfEvents, eventTimeslotShare);
    populateBoolMatrixWithFileLine(fp, p.numberOfEvents, p.numberOfRooms, eventRoomFit);
    populateBoolMatrixWithFileLine(fp, p.numberOfEvents, p.numberOfEvents, eventSameSubject);
    populateIntArrayWithFileLine(fp, p.numberOfEvents, eventBlockSize);
    populateBoolMatrixWithFileLine(fp, p.numberOfTimeslots, p.numberOfTimeslots, timeslotNeighborhood);

    p.numberOfBlocks = getIntFromFileLine(fp);
    struct Node *eventBlock[p.numberOfBlocks];
    populateBlockArray(fp, p.numberOfBlocks, eventBlock);

    flatTimeslotNeighborhoodMatrix(p, timeslotNeighborhood, timeslotNeighborhoodFlat);

    fclose(fp);

    doEvolution(
            p,
            eventTimeslotShare,
            eventRoomFit,
            eventSameSubject,
            eventBlockSize,
            timeslotNeighborhoodFlat,
            eventBlock,
            100000000
    );


    return 0;
}




