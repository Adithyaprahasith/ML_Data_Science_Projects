# Flight Fare Prediction using Machine Learning

Predicting airline ticket prices using **real-time flight data** scraped from [Kayak](https://www.kayak.com/), leveraging **Python and Machine Learning** to aid travelers and travel businesses in making informed decisions.

---

##  Project Overview
Airline ticket prices fluctuate frequently, making it challenging for travelers to identify the best time to book.  
This project builds a **Machine Learning pipeline** to **predict flight fares** by:

1. **Scraping real-time flight data** from Kayak  
2. **Cleaning & preprocessing** data for modeling  
3. **Training regression models** to forecast ticket prices  
4. **Evaluating model performance** to identify the most accurate predictor  

---

##  Dataset
- **Source:** Scraped from Kayak using **Selenium & BeautifulSoup**  
- **Records:** 2,285 flight listings  
- **Features:**
  - `Airline` – Name of the airline  
  - `Flight` – Flight number  
  - `Flight_Duration` – Duration of the flight  
  - `Stops` – Number of stops  
  - `Time_total` – Departure & arrival times  
  - `Class_type` – Economy/Business class  
  - `Price` – Target variable (ticket price)  

---

##  Tech Stack
- **Programming:** Python  
- **Libraries:** Pandas, NumPy, Scikit-learn, Matplotlib, Seaborn  
- **Web Scraping:** Selenium, BeautifulSoup  
- **Machine Learning:** Decision Tree, Random Forest, KNN, Support Vector Regressor  

---

##  Workflow
1. **Data Collection**
   - Scraped 2,200+ flight listings using **Selenium WebDriver**  
   - Extracted details like flight name, stops, timings, and ticket price  

2. **Data Cleaning & Preprocessing**
   - Removed duplicates & handled missing values  
   - Label encoded categorical columns  
   - Explored data using **correlation matrix & EDA visualizations**  

3. **Model Training & Evaluation**
   - Implemented **Decision Tree, Random Forest, KNN, SVR** regressors  
   - Evaluated using **R² Score**  
   - **Random Forest achieved the best performance with 87% R²**  

4. **Future Scope**
   - Deploy as a **web app** for real-time fare prediction  
   - Integrate **automated daily scraping** for continuous updates  

---

##  Results
- **Best Model:** Random Forest Regressor  
- **Performance:** Achieved **87% R² Score**  
- **Impact:** Accurate fare predictions for optimal booking decisions  



