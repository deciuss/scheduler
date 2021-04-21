#include <limits.h>

int randInt(int lower, int upper) {
    return (rand() % (upper - lower + 1)) + lower;
}

void populateBoolMatrix(int sizeX, int sizeY, bool matrix[sizeX][sizeY], bool value) {
    for (int i = 0; i < sizeX; i++)
        for (int j = 0; j < sizeY; j++)
            matrix[i][j] = value;
}

void copyIntMatrix(int sizeX, int sizeY, int oryginal[sizeX][sizeY], int replica[sizeX][sizeY]) {
    for (int i = 0; i < sizeX; i++)
        for (int j = 0; j < sizeY; j++)
            replica[i][j] = oryginal[i][j];
}

int calculateHardViolation(
        struct Params p,
        int individual[p.numberOfEvents][3],
        bool eventTimeslotShare[p.numberOfEvents][p.numberOfEvents]
) {
    int violation = 0;
    bool roomTimeslotPairUsed[p.numberOfTimeslots][p.numberOfRooms];
    populateBoolMatrix(p.numberOfTimeslots, p.numberOfRooms, roomTimeslotPairUsed, false);

    for (int i = 0; i < p.numberOfEvents; i++) {

        if (roomTimeslotPairUsed[individual[i][0]][individual[i][1]] == true) {
            violation++;
            individual[i][2]++;
        }
        roomTimeslotPairUsed[individual[i][0]][individual[i][1]] = true;

        for (int j = i; j < p.numberOfEvents; j++) {
            if (i == j) continue;
            if (individual[i][0] != individual[j][0]) continue;
            if (eventTimeslotShare[i][j] == false) {
                violation++;
                individual[i][2]++;
            }
        }
    }
    return violation * p.hardViolationFactor;
}

//int calculateSoftViolation(
//        int numberOfEvents,
//        int individual[numberOfEvents][3],
//        bool eventSameSubject[numberOfEvents][numberOfEvents],
//        int eventBlockSize[numberOfEvents]
//) {
//    int violation = 0;
//    for (int i = 0; i < numberOfEvents; i++) {
//
//    }
//    return violation;
//}

int getRoomForEvent(struct Params p, bool eventRoomFit[p.numberOfEvents][p.numberOfRooms], int event) {
    int room;
    while (1) {
        room = randInt(0, p.numberOfRooms - 1);
        if (eventRoomFit[event][room] == true) return room;
    }
}

void initializeIndividual(
        struct Params p,
        bool eventRoomFit[p.numberOfEvents][p.numberOfRooms],
        int individual[p.numberOfEvents][3]
) {
    for (int j = 0; j < p.numberOfEvents; j++) {
        individual[j][0] = randInt(0, p.numberOfTimeslots - 1);
        individual[j][1] = getRoomForEvent(p, eventRoomFit, j);
        individual[j][2] = 0;
    }
}

void initializePopulation(
        struct Params p,
        bool eventRoomFit[p.numberOfEvents][p.numberOfRooms],
        int population[p.populationCardinality][p.numberOfEvents][3]
) {
    for (int i = 0; i < p.populationCardinality; i++) {
        initializeIndividual(p, eventRoomFit, population[i]);
    }
}

int findBestIndividual(
        struct Params p,
        int population[p.populationCardinality][p.numberOfEvents][3],
        bool eventTimeslotShare[p.numberOfEvents][p.numberOfEvents],
        int rangeMin,
        int rangeMax
) {
    int bestIndividualIndex;
    int bestIndividualViolation = INT_MAX;

    for (int i = rangeMin; i < rangeMax; i++) {
        int violation = calculateHardViolation(
                p,
                population[i],
                eventTimeslotShare
        );
        if (violation < bestIndividualViolation) {
            bestIndividualViolation = violation;
            bestIndividualIndex = i;
        }
    }

    printf("Sourvivor violation factor: %d\n", bestIndividualViolation);

    return bestIndividualIndex;
}

void selectSurvivors(
        struct Params p,
        int population[p.populationCardinality][p.numberOfEvents][3],
        int broodSplit[p.numberOfSurvivors][2],
        bool eventTimeslotShare[p.numberOfEvents][p.numberOfEvents],
        int survivorIdexes[p.numberOfSurvivors]
) {

    for (int i = 0; i < p.numberOfSurvivors; i++) {
        survivorIdexes[i] = findBestIndividual(
                p,
                population,
                eventTimeslotShare,
                broodSplit[i][0],
                broodSplit[i][1]
        );
    }
}

void reproduce(
        struct Params p,
        bool eventRoomFit[p.numberOfEvents][p.numberOfRooms],
        int population[p.populationCardinality][p.numberOfEvents][3],
        int child[p.numberOfEvents][3]
) {
    for (int i = 0; i < p.numberOfEvents; i++) {
//        int selectedParentGene = randInt(0, p.numberOfSurvivors - 1);
        int selectedParentGene = p.numberOfSurvivors - 1;
        int parentGeneMinimalViolation = INT_MAX;
        for (int j = p.numberOfSurvivors - 1; j >= 0; j--) {
//        for (int j = 0; j < p.numberOfSurvivors; j++) {
            if (population[j][i][2] < parentGeneMinimalViolation && randInt(0, 999) > 500) {
                parentGeneMinimalViolation = population[j][i][2];
                selectedParentGene = j;
            }
        }
        child[i][0] = population[selectedParentGene][i][0];
        child[i][1] = population[selectedParentGene][i][1];
        child[i][2] = 0;
    }

}

void nextGeneration(
        struct Params p,
        bool eventRoomFit[p.numberOfEvents][p.numberOfRooms],
        int survivorIdexes[p.numberOfSurvivors],
        int population[p.populationCardinality][p.numberOfEvents][3]
) {
    int i = 0;
    for (; i < p.numberOfSurvivors; i++) {
        copyIntMatrix(p.numberOfEvents, 3, population[survivorIdexes[i]], population[i]);
    }
    for (; i < 15; i++) {
        reproduce(p, eventRoomFit, population, population[i]);
    }
    for (; i < p.populationCardinality; i++) {
        initializeIndividual(p, eventRoomFit, population[i]);
    }
}

void mutate(struct Params p, bool eventTimeslotShare[p.numberOfEvents][p.numberOfEvents], int individual[p.numberOfEvents][3]) {
    int badGeneIndex1 = -1;
    int badGeneIndex2 = -1;

    int initialViolation = calculateHardViolation(p, individual, eventTimeslotShare);

    while (badGeneIndex1 < 0) {
        int candidate = randInt(0, p.numberOfEvents - 1);
        if (individual[candidate][2] > 0) {
            badGeneIndex1 = candidate;
        }
    }

    while (badGeneIndex2 < 0) {
        int candidate = randInt(0, p.numberOfEvents - 1);
        if (candidate == badGeneIndex1) continue;
        if (individual[candidate][2] > 0) {
            badGeneIndex2 = candidate;
        }
    }

    int tmpTimeslot = individual[badGeneIndex1][0];
    individual[badGeneIndex1][0] = individual[badGeneIndex2][0];
    individual[badGeneIndex2][0] = tmpTimeslot;

    if (calculateHardViolation(p, individual, eventTimeslotShare) > initialViolation) {
        int tmpTimeslot = individual[badGeneIndex1][0];
        individual[badGeneIndex1][0] = individual[badGeneIndex2][0];
        individual[badGeneIndex2][0] = tmpTimeslot;
    }
}

void doEvolution(
        struct Params p,
//        int bestIndividual[p.numberOfEvents][3],
        bool eventTimeslotShare[p.numberOfEvents][p.numberOfEvents],
        bool eventRoomFit[p.numberOfEvents][p.numberOfRooms],
        int numberOfGenerations
) {

    int broodSplit[][2] = {{0, 4}, {5, 10}, {11, 15}, {16, 30}};
    int survivorIdexes[p.numberOfSurvivors];

    int population[p.populationCardinality][p.numberOfEvents][3];
    initializePopulation(p, eventRoomFit, population);

    for (int generation = 0; generation < numberOfGenerations; generation++) {

        selectSurvivors(p, population, broodSplit, eventTimeslotShare, survivorIdexes);

        for (int survivor = 0; survivor < p.numberOfSurvivors; survivor++) {
            mutate(p, eventTimeslotShare, population[survivorIdexes[survivor]]);
        }
        for (int survivor = 0; survivor < p.numberOfSurvivors; survivor++) {
            mutate(p, eventTimeslotShare, population[survivorIdexes[survivor]]);
        }
        for (int survivor = 0; survivor < p.numberOfSurvivors; survivor++) {
            mutate(p, eventTimeslotShare, population[survivorIdexes[survivor]]);
        }
        for (int survivor = 0; survivor < p.numberOfSurvivors; survivor++) {
            mutate(p, eventTimeslotShare, population[survivorIdexes[survivor]]);
        }

        printf("Survivors indexes: %d, %d, %d, %d\n", survivorIdexes[0], survivorIdexes[1], survivorIdexes[2], survivorIdexes[3]);

        nextGeneration(p, eventRoomFit, survivorIdexes, population);


    }


}