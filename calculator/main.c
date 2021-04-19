#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <stdbool.h>

int randInt(int lower, int upper) {
    return (rand() % (upper - lower + 1)) + lower;
}

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

void initializePopulation(
        int populationCardinality,
        int numberOfEvents,
        int numberOfTimeslots,
        int numberOfRooms,
        int population[populationCardinality][numberOfEvents][2]
) {
    for (int i = 0; i < populationCardinality; i++) {
        for (int j = 0; j < numberOfEvents; j++) {
            population[i][j][0] = randInt(0, numberOfTimeslots - 1);
            population[i][j][1] = randInt(0, numberOfRooms - 1);
        }

    }
}

int calculateHardViolation(
        int numberOfEvents,
        int numberOfRooms,
        int violationFactor,
        int individual[numberOfEvents][2],
        bool eventTimeslotShare[numberOfEvents][numberOfEvents],
        bool eventRoomFit[numberOfEvents][numberOfRooms]
) {

}

int main(int argc, char * argv[]) {

    srand(time(0));

    FILE *fp;
    fp = fopen("../../var/calculator/calculator_file", "r");

    int numberOfEvents = getIntFromFileLine(fp);
    int numberOfRooms = getIntFromFileLine(fp);
    int numberOfTimeslots = getIntFromFileLine(fp);

    bool eventTimeslotShare[numberOfEvents][numberOfEvents];
    bool eventRoomFit[numberOfEvents][numberOfRooms];
    bool eventSameSubject[numberOfEvents][numberOfEvents];
    int eventBlockSize[numberOfEvents];

    populateBoolMatrixWithFileLine(fp, numberOfEvents, numberOfEvents, eventTimeslotShare);
    populateBoolMatrixWithFileLine(fp, numberOfEvents, numberOfRooms, eventRoomFit);
    populateBoolMatrixWithFileLine(fp, numberOfEvents, numberOfEvents, eventSameSubject);
    populateIntArrayWithFileLine(fp, numberOfEvents, eventBlockSize);

    fclose(fp);

    int populationSize = 10;

    int population[populationSize][numberOfEvents][2];

    initializePopulation(populationSize, numberOfEvents, numberOfTimeslots, numberOfRooms, population);






    return 0;
}




