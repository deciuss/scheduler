void populateBoolMatrix(int sizeX, int sizeY, bool matrix[sizeX][sizeY], bool value) {
    for (int i = 0; i < sizeX; i++)
        for (int j = 0; j < sizeY; j++)
            matrix[i][j] = value;
}

void populateIntMatrix(int sizeX, int sizeY, int matrix[sizeX][sizeY], bool value) {
    for (int i = 0; i < sizeX; i++)
        for (int j = 0; j < sizeY; j++)
            matrix[i][j] = value;
}

writeIntMatrixToCsvFile(int sizeX, int sizeY, int matrix[sizeX][sizeY], const char * filename) {
    FILE *fp;
    fp = fopen(filename, "w+");
    fprintf(fp, "timeslot,room,violation");
    bool firstRow = true;
    for (int i = 0; i < sizeX; i++) {
        if (!firstRow) fprintf(fp, "\n");
        firstRow = false;
        bool firstColumn = true;
        for (int j = 0; j < sizeY; j++) {
            if (!firstColumn) fprintf(fp, ",");
            firstColumn = false;
            fprintf(fp, "%d", matrix[i][j]);
        }
    }
    fclose(fp);
}
