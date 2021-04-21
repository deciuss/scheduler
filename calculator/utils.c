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
