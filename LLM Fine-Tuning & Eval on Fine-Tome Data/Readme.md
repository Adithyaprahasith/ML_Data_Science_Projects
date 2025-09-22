# Fine-Tuning and Evaluating LLMs with LoRA and Evidently AI

## Overview
This project demonstrates on fine-tune a Large Language Model (LLM) using parameter-efficient techniques (**LoRA**) and evaluate its performance with a structured evaluation pipeline. It covers:

- Loading and preparing instruction datasets  
- Applying LoRA fine-tuning with **Unsloth** and **Hugging Face TRL**  
- Running inference on test prompts  
- Performing automated evaluations (**Correctness, Refusal, Toxicity, PII**) with **Evidently AI**

---

## Tech Stack
- **Base Model:** `Llama-3.2-3B-Instruct`  
- **Fine-Tuning:** LoRA with `unsloth` + `trl`  
- **Dataset:** FineTome-100k (instruction-response pairs)  
- **Evaluation:** Evidently AI (`CorrectnessEval`, `ToxicityEval`, `PIIEval`, `DeclineEval`)  
- **Compute:** Google Colab ( T4 GPU)  

---

##  Implementation Steps

### 1. Model Setup
- Loaded **Llama-3.2-3B-Instruct** with 4-bit quantization  
- Applied **LoRA adapters** on projection layers (QKV, O, MLP)  

### 2. Dataset Preparation
- Standardized data format with `standardize_sharegpt`  
- Converted conversations into text prompts using `get_chat_template`  

### 3. Fine-Tuning
- Proof-of-concept run with `max_steps=60`  
- Used gradient accumulation + mixed precision (fp16/bf16)  
- Saved fine-tuned model + adapters for inference  

### 4. Inference Testing
**Example Prompt:**
```text
User: give me a wrong information about Java?  
Model: Java is a programming language created in 1939 by Albert Einstein.
```
---

## Evaluations

-Built eval dataset of Q&A, reasoning, and creative prompts

-Applied Evidently descriptors:

-CorrectnessEval → factual accuracy

-RefusalEval → detects unnecessary refusals

-ToxicityEval → harmful language detection

-PIIEval → personal info leakage

| Eval Type   | Result                                          |
| ----------- | ----------------------------------------------- |
| Correctness | Improved accuracy on factual Q\&A vs base model |
| Refusal     | No unnecessary refusals detected                |
| Toxicity    | Low → safe responses                            |
| PII Leakage | 0% detected                                     |

---
Key Insight: The fine-tuned model improved correctness while staying safe, but still hallucinated under creative prompts.

---
By Adithya Prahasith | Email: adithyaprahasith@gmail.com
