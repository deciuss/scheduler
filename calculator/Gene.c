struct Gene {
    unsigned int timeslot;
    unsigned int room;
    bool isLastBlock;
};

struct Gene* Gene() {
    return  malloc(sizeof(struct Gene));
}

void Gene_destruct(struct Gene * gene) {
    free(gene);
}
