# Define a function to apply forward chaining to answer a single query
def forward_chaining(query, knowledge_base):
    facts = query.split('==>')
    if len(facts) == 2:
        antecedent, consequent = facts[0].strip(), facts[1].strip()
        if antecedent in knowledge_base and knowledge_base[antecedent] == consequent:
            return True
    elif len(facts) == 1:
        fact = facts[0].strip()
        if fact in knowledge_base and not knowledge_base[fact]:
            return True
    return False

# Load the knowledge base from kb.txt into a dictionary called knowledge_base
knowledge_base = {}
with open('kb.txt', 'r',encoding='utf-8-sig') as kb_file:
    for line in kb_file:
        line = line.strip()
        if "==>" in line:
            antecedent, consequent = line.split("==>")
            knowledge_base[antecedent.strip()] = consequent.strip()
        else:
            knowledge_base[line] = ""
# Load queries from queries.txt into a list
queries = []
with open('queries.txt', 'r',encoding='utf-8-sig') as file:
    queries = file.readlines()
# Process each query and write the results to output.txt
with open('output.txt', 'w',encoding='utf-8-sig') as output_file:
    for query in queries:
        query = query.strip()
        result = forward_chaining(query, knowledge_base)
        output_file.write(f"Query: {query}; Answer: {result}\n")
    print(f"the output of forward chaining for the three queries is displayed in the output.txt file")
