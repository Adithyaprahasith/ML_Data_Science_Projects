#  AI Assistant for Data Analysis using GPT-4o + LangChain

A conversational AI assistant that enables users to analyze structured CSV datasets without writing SQL or Python code. Powered by GPT-4o, LangChain agents, and Streamlit, this tool transforms raw data into actionable insights and visualizations through natural language queries.

---

## Project Overview

The goal of this project is to democratize data analysis by building an intelligent assistant that:
- Accepts natural language questions
- Analyzes CSV datasets in real-time
- Generates insights, summaries, and visualizations
- Eliminates the need for manual SQL or EDA scripting

---

## Tech Stack

- **LLM**: OpenAI GPT-4o
- **Framework**: LangChain (`create_csv_agent`)
- **UI**: Streamlit
- **Language**: Python (pandas, re, sys, subprocess)
- **Dataset**: Black Friday Sales (550,000+ records)

---

## Dataset Details

- Source: Stratascratch
- Size: ~550K customer transactions
- Fields: Gender, Age, City, Product Categories, Purchase Amount, etc.
- Objective: Analyze if male vs. female spending differs on Black Friday

---

## Key Features

- **GPT-4o Integration**: Few-shot prompted agent handles CSV-based analytical queries
- **Smart Query Validation**: Filters out irrelevant questions to reduce hallucinations
- **Streamlit Interface**: Clean, interactive UI for seamless LLM interaction

---

## Insights Overview

- Male customers contributed to 60% of total purchases
- Majority of high-spending males were aged 26–35
- City B showed the highest purchasing activity among singles

---

## Limitations

- Occasional response parsing issues in live UI due to token limits
- Visualization code may require manual execution in some environments

---

## Links


- 💻 [Source Code](https://github.com/Adithyaprahasith/ml_projects/tree/main/AI%20Agent%20for%20Data%20Analysis)


---

## By

**Adithya Prahasith**  
📧 adithyaprahasith@gmail.com  
🔗 [LinkedIn](https://www.linkedin.com/in/adithya-prahasith/)


