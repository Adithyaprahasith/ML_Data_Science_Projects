import pandas as pd
import streamlit as st
import re
import sys
import subprocess
import importlib.util
# Set page config
st.set_page_config(page_title="Data Analysis Agent", layout="wide")

api_key="the openai api key here"
# Check and install required packages
required_packages = ['tabulate', 'langchain_experimental', 'langchain', 'openai']
for package in required_packages:
    if importlib.util.find_spec(package) is None:
        st.write(f"Installing {package}...")
        subprocess.check_call([sys.executable, "-m", "pip", "install", package])

from langchain_experimental.agents import create_csv_agent
from langchain.chat_models import ChatOpenAI
from langchain.agents import AgentExecutor
from langchain.callbacks.base import BaseCallbackHandler

# Custom callback handler to intercept and process errors
class ErrorInterceptor(BaseCallbackHandler):
    def __init__(self):
        self.errors = []
    
    def on_llm_error(self, error, **kwargs):
        self.errors.append(str(error))
    
    def on_chain_error(self, error, **kwargs):
        self.errors.append(str(error))
    
    def on_tool_error(self, error, **kwargs):
        self.errors.append(str(error))

# Custom prompt template for the agent
CUSTOM_PREFIX = """You are a data analyst assistant. Your sole purpose is to analyze the Walmart dataset provided.
You should:
- Provide initial observations and insights about the data when asked
- Plot graphs or provide code for visualizations when specifically requested
- Answer questions directly related to analyzing the Walmart dataset
- DO NOT answer any questions unrelated to data analysis or the Walmart dataset
- If your answer includes a Python code for visualization, make sure it's properly formatted and ready to run
- Always ensure your responses can be parsed correctly by following the expected output format
- ALWAYS use the entire dataset (all rows) for analysis, not just the sample
- Report the total number of records used in your analysis when relevant
If asked about anything other than the Walmart dataset analysis, politely decline and remind the user that you can only help with analyzing the Walmart dataset.
"""

# Function to check if the question is relevant to the CSV data
def is_question_relevant_to_data(question, data_columns):
    # Check if question is about data analysis or Walmart dataset
    data_analysis_keywords = ['analyze', 'analysis', 'insights', 'patterns', 'trends', 'statistics', 
                              'average', 'mean', 'median', 'plot', 'graph', 'visualization', 'chart',
                              'walmart', 'sales', 'revenue', 'store', 'product', 'category','record','plot']
    
    question = question.lower()
    
    # Check for data analysis keywords
    for keyword in data_analysis_keywords:
        if keyword in question:
            return True
            
    # Check for column names
    for column in data_columns:
        if column.lower() in question:
            return True
            
    return False

# Extract useful content from error messages
def extract_useful_content(error_message):
    # Look for content after parsing error messages
    parsing_error_patterns = [
        r"Could not parse LLM output: `(.*)",
        r"parsing error.*:(.*)$"
    ]
    
    for pattern in parsing_error_patterns:
        match = re.search(pattern, error_message, re.DOTALL | re.IGNORECASE)
        if match:
            # Found content after parsing error
            return match.group(1).strip()
    
    return None

# Custom agent runner with improved error handling
def ask_question(agent, question, data_columns):
    # Check if the question is related to data analysis of the Walmart dataset
    if is_question_relevant_to_data(question, data_columns):
        try:
            # Create an error interceptor
            error_handler = ErrorInterceptor()
            
            # Run the agent with error handling
            response = agent.run(question)
            return response
        except Exception as e:
            error_str = str(e)
            
            # Try to extract useful content from error
            useful_content = extract_useful_content(error_str)
            if useful_content:
                # If we found valid content in the error, return it
                return useful_content
            
            # If we can't extract useful content, provide a generic response
            return "I've analyzed your question about the Walmart dataset. While I understand what you're asking, I'm having trouble generating a complete response. Could you try rephrasing your question or asking about a specific aspect of the data?"
    else:
        return "I'm a data analyst assistant focused only on the Walmart dataset. I can analyze the data, provide insights, or create visualizations when asked. Please ask questions related to analyzing the Walmart data."

# Sidebar for API key input and dataset info
with st.sidebar:
    st.title("Data overview")
    
    st.markdown("---")
    st.subheader(" Dataset info")
    
    # Load dataset for preview if available
    try:
        data = pd.read_csv("path of the dataset")
        data_columns = data.columns.tolist()
        
        st.write(f"Total records: {len(data)}")
        st.write("Columns:")
        st.write(", ".join(data_columns))
        
        with st.expander("Preview Dataset"):
            st.dataframe(data.head())
    except Exception as e:
        st.error(f"Error loading dataset: {str(e)}")
        data_columns = []

# Main content area
st.title("AI Agent for Data Analysis")
st.markdown("""
Ask questions about the given dataset to get insights and visualizations.
""")

# Initialize chat history in session state if it doesn't exist
if "messages" not in st.session_state:
    st.session_state.messages = []

# Display chat history
for message in st.session_state.messages:
    with st.chat_message(message["role"]):
        st.markdown(message["content"])

# Chat input
prompt = st.chat_input("Ask questions about analyzing the Walmart dataset...")

# Process the user query
if prompt:
    # Add user message to chat history
    st.session_state.messages.append({"role": "user", "content": prompt})
    
    # Display user message
    with st.chat_message("user"):
        st.markdown(prompt)
    
    # Display assistant response
    with st.chat_message("assistant"):
        if not api_key:
            st.error("Please enter your OpenAI API key in the sidebar to continue.")
        elif not data_columns:
            st.error("Unable to access the dataset. Please check if 'walmart_.csv' exists in the current directory.")
        else:
            message_placeholder = st.empty()
            message_placeholder.markdown("Analyzing...")
            
            # Create the agent with custom prompt
            try:
                agent = create_csv_agent(
                    ChatOpenAI(model="gpt-4o", temperature=0, openai_api_key=api_key),
                    "path of dataset",
                    verbose=False,
                    handle_parsing_errors=True,  # Make sure this is set to True
                    prefix=CUSTOM_PREFIX # few shot prompts
                )
                
                # Get response from agent with improved error handling
                response = ask_question(agent, prompt, data_columns)
                
                # Clean up any markdown or code formatting issues
                response = response.replace("```python", "```python\n").replace("```", "\n```")
                
                # Display response
                message_placeholder.markdown(response)
                
                # Add assistant response to chat history
                st.session_state.messages.append({"role": "assistant", "content": response})
            except Exception as e:
                # Generic error message that doesn't expose technical details
                error_message = "I'm having trouble analyzing the data right now. Please try a different question about the Walmart dataset."
                message_placeholder.markdown(error_message)
                st.session_state.messages.append({"role": "assistant", "content": error_message})

# Footer
st.markdown("---")
st.caption(" AI Agent for Data Analysis - Powered by LangChain and OpenAI")
