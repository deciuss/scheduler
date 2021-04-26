struct Population {
    struct Individual** individuals;
    int size;
};

struct Population* Population(int size) {
    struct Population* population = malloc(sizeof(struct Population));
    population->size = size;
    population->individuals = malloc(sizeof(struct Individual*) * size);
}
