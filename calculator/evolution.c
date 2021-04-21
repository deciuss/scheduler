#include <limits.h>

int randInt(int lower, int upper) {
    return (rand() % (upper - lower + 1)) + lower;
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

void populateTimeslotRoomEventMatrix(
        struct Params p,
        int individual[p.numberOfEvents][3],
        int timeslotRoomEventMatrix[p.numberOfTimeslots][p.numberOfRooms]
) {
    populateIntMatrix(p.numberOfTimeslots, p.numberOfRooms, timeslotRoomEventMatrix, -1);

    for (int i = 0; i < p.numberOfEvents; i++) {
        timeslotRoomEventMatrix[individual[i][0]][individual[i][1]] = i;
    }
}

int calculateSoftViolation(
        struct Params p,
        int individual[p.numberOfEvents][3],
        bool eventSameSubject[p.numberOfEvents][p.numberOfEvents],
        int eventBlockSize[p.numberOfEvents],
        int timeslotNeighborhoodFlat[p.numberOfTimeslots][2]
) {
    int violation = 0;

    int timeslotRoomEventMatrix[p.numberOfTimeslots][p.numberOfRooms];
    populateTimeslotRoomEventMatrix(p, individual, timeslotRoomEventMatrix);

    bool timeslotRoomChacked[p.numberOfTimeslots][p.numberOfRooms];
    populateBoolMatrix(p.numberOfTimeslots, p.numberOfRooms, timeslotRoomChacked, false);



    for (int room = 0; room < p.numberOfRooms; room++) {

        for (int timeslot = 0; timeslot < p.numberOfTimeslots; timeslot++) {
            if (timeslotRoomChacked[timeslot][room] == true) continue;
            timeslotRoomChacked[timeslot][room] = true;


            int preferredBlockSize = eventBlockSize[timeslotRoomEventMatrix[timeslot][room]];
            for (int remainingBlockSize = preferredBlockSize - 1; remainingBlockSize > 0; remainingBlockSize--) {
//                timeslotRoomEventMatrix[timeslotNeighborhoodFlat[][1]][room]
            }



        }

    }

    return violation;
}

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
        bool eventTimeslotShare[p.numberOfEvents][p.numberOfEvents],
        int survivorIdexes[p.numberOfSurvivors]
) {

    for (int i = 0; i < p.numberOfSurvivors; i++) {
        survivorIdexes[i] = findBestIndividual(
                p,
                population,
                eventTimeslotShare,
                p.broodSplit[i][0],
                p.broodSplit[i][1]
        );
    }
}

void reproduce(
        struct Params p,
        bool eventRoomFit[p.numberOfEvents][p.numberOfRooms],
        int parents[2][p.numberOfEvents][3],
        int child[p.numberOfEvents][3]
) {
    for (int i = 0; i < p.numberOfEvents; i++) {
        int selectedParentGene = randInt(0, 1);
        child[i][0] = parents[selectedParentGene][i][0];
        child[i][1] = parents[selectedParentGene][i][1];
        child[i][2] = 0;
    }

}

void mutation1 (
        struct Params p,
        int individual[p.numberOfEvents][3]
) {
    for (int i = 0; i < p.mutation1Rate; i++) {
        int eventIndex1 = randInt(0, p.numberOfEvents - 1);
        int eventIndex2 = eventIndex1;
        while (eventIndex1 == eventIndex2) {
            eventIndex2 = randInt(0, p.numberOfEvents - 1);
        }
        int tmpTimeslot = individual[eventIndex1][0];
        individual[eventIndex1][0] = individual[eventIndex2][0];
        individual[eventIndex2][0] = tmpTimeslot;
    }
}

void mutation2 (
        struct Params p,
        bool eventRoomFit[p.numberOfEvents][p.numberOfRooms],
        int individual[p.numberOfEvents][3]
) {
    for (int i = 0; i < p.mutation2Rate; i++) {
        int eventIndex1 = randInt(0, p.numberOfEvents - 1);
        individual[eventIndex1][0] = randInt(0, p.numberOfTimeslots - 1);
        individual[eventIndex1][1] = getRoomForEvent(p, eventRoomFit, eventIndex1);
    }
}

void mutate(
        struct Params p,
        bool eventRoomFit[p.numberOfEvents][p.numberOfRooms],
        int individual[p.numberOfEvents][3]
) {
    mutation1(p, individual);
    mutation2(p, eventRoomFit, individual);
}

void nextGeneration(
        struct Params p,
        bool eventTimeslotShare[p.numberOfEvents][p.numberOfEvents],
        bool eventRoomFit[p.numberOfEvents][p.numberOfRooms],
        int survivorIdexes[p.numberOfSurvivors],
        int population[p.populationCardinality][p.numberOfEvents][3]
) {
    int parents[2][p.numberOfEvents][3];

    copyIntMatrix(p.numberOfEvents, 3, population[survivorIdexes[0]], parents[0]);
    copyIntMatrix(p.numberOfEvents, 3, population[survivorIdexes[1]], parents[1]);

    for (int i = 0; i < p.populationCardinality; i++) {
        reproduce(p, eventRoomFit, parents, population[i]);
        mutate(p, eventRoomFit, population[i]);
    }
}

void doEvolution(
        struct Params p,
        bool eventTimeslotShare[p.numberOfEvents][p.numberOfEvents],
        bool eventRoomFit[p.numberOfEvents][p.numberOfRooms],
        int numberOfGenerations
) {

    int survivorIdexes[p.numberOfSurvivors];

    int population[p.populationCardinality][p.numberOfEvents][3];
    initializePopulation(p, eventRoomFit, population);

    int lowestViolation = INT_MAX;

    for (int generation = 0; generation < numberOfGenerations; generation++) {

        selectSurvivors(p, population, eventTimeslotShare, survivorIdexes);

        for (int j = 0; j < p.numberOfSurvivors; j++) {
            int violation = calculateHardViolation(p, population[survivorIdexes[j]], eventTimeslotShare);
            if (violation < lowestViolation) {
                lowestViolation = violation;
            }
        }

        printf("Generation: %d; Lowest violation: %d; Survivors indexes: %d, %d\n", generation, lowestViolation, survivorIdexes[0], survivorIdexes[1]);

        nextGeneration(p, eventTimeslotShare, eventRoomFit, survivorIdexes, population);


    }


}