struct Node {
    int val;
    struct Node* next;
};

struct Node* Node_getNextNode(struct Node* node) {
    return node->next;
}

struct Node* Node_getLasNode(struct Node* node) {
    struct Node* next = Node_getNextNode(node);
    if (next == NULL) {
        return node;
    }
    return Node_getLasNode(next);
}

void Node_add(struct Node* node, int val) {

//    if (node == NULL) {
//        node = malloc(sizeof(*node));
//        node->val = val;
//        return;
//    }

    struct Node* lastNode = Node_getLasNode(node);
    struct Node* newNode = malloc(sizeof(*newNode));
    newNode->next = NULL;
    newNode->val = val;
    lastNode->next = newNode;
}

struct Node* Node_addOrCreate(struct Node* node, int val) {
    if (node == NULL) {
        node = malloc(sizeof(*node));
        node->next = NULL;
        node->val = val;
    } else {
        Node_add(node, val);
    }
    return node;
}

void Node_deleteWithAllLinks(struct Node* node) {
    struct Node* next = Node_getNextNode(node);
    if (next == NULL) {
        free(node);
    } else {
        Node_deleteWithAllLinks(next);
        free(node);
    }
}

bool Node_containsValue(struct Node* node, int val) {

    if (node->val == val) return true;
    struct Node* next = Node_getNextNode(node);
    if (next != NULL) {
        return Node_containsValue(next, val);
    }
    return false;
}
