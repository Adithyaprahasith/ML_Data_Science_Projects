#loading the data
#initial overview of data
#cleaning the data - keeping required cols and impute missing values
#some data visualizations 
# label encoding for some cols 
# normalization of values 
#train and test models LR,DT,RF,XGB

#loading the data and intial overview
data<- read.csv('/Users/adithyaprahasith/Downloads/marketing_campaign.csv',sep = '\t')
head(data)
View(data)
str(data)
summary(data)
table(data$Response)

barplot(table(data$Response))
#keep required cols and impute
data$ID<-NULL
data$Year_Birth<-NULL
data$Dt_Customer<-NULL
data$Z_Revenue<-NULL
data$Z_CostContact<-NULL
names(which(colSums(is.na(data)) > 0))
data$Income <-replace(data$Income, is.na(data$Income), mean(data$Income, na.rm = TRUE))
data$num_children<-data$Kidhome+data$Teenhome
data$Kidhome<-NULL
data$Teenhome<-NULL

data$Marital_Status[data$Marital_Status == 'Alone'] <- 'Single'
data$Marital_Status[data$Marital_Status == 'Widow'] <- 'Divorced'
data <- data[data$Marital_Status != "YOLO", ]
data <- data[data$Marital_Status != "Absurd", ]

data$Education[data$Education == '2n Cycle'] <- 'Basic'

# 4. Product Category Performance
product_data <- data %>%
  group_by(Education) %>%
  summarise(across(starts_with("Mnt"), mean)) %>%
  pivot_longer(cols = starts_with("Mnt"), 
               names_to = "Product", 
               values_to = "Average_Spending")

ggplot(product_data, aes(x = Education, y = Average_Spending, fill = Product)) +
  geom_bar(stat = "identity", position = "stack") +
  theme_minimal() +
  labs(title = "Product Category Spending by Education Level") +
  theme(axis.text.x = element_text(angle = 45))


data$amount_spent<-data$MntWines + data$MntFruits + data$MntMeatProducts + data$MntFishProducts + data$MntSweetProducts + data$MntGoldProds
data$MntWines<-NULL
data$MntFruits<-NULL
data$MntMeatProducts<-NULL
data$MntFishProducts<-NULL
data$MntSweetProducts<-NULL
data$MntGoldProds<-NULL

# Load libraries
library(ggplot2)
library(dplyr)

# Bar Plot: Response by Education
response_by_education <- data %>%
  group_by(Education, Response) %>%
  summarise(Count = n(), .groups = 'drop')

ggplot(response_by_education, aes(x = Education, y = Count, fill = factor(Response))) +
  geom_bar(stat = "identity", position = "dodge") +
  labs(title = "Response by Education",
       x = "Education Level",
       y = "Count",
       fill = "Response") +
  theme_minimal() +
  theme(axis.text.x = element_text(angle = 45, hjust = 1))


# Bar Plot: Response by Marital Status
response_by_marital_status <- data %>%
  group_by(Marital_Status, Response) %>%
  summarise(Count = n(), .groups = 'drop')

ggplot(response_by_marital_status, aes(x = Marital_Status, y = Count, fill = factor(Response))) +
  geom_bar(stat = "identity", position = "dodge") +
  labs(title = "Response by Marital Status",
       x = "Marital Status",
       y = "Count",
       fill = "Response") +
  theme_minimal() +
  theme(axis.text.x = element_text(angle = 45, hjust = 1))

# Income vs Spending plot
ggplot(data, aes(x = log(Income), y = log(amount_spent))) +
  geom_point(alpha = 0.5, color = "darkgreen") +
  geom_smooth(method = "lm", se = TRUE, color = "red") +
  labs(title = "Income vs Spending Correlation",
       x = "Yearly Household Income",
       y = "Total Spending (all products)") +
  theme_minimal()


df <- data %>%
  mutate(TotalSpending = amount_spent)

ggplot(df, aes(x = Recency, y = TotalSpending, color = factor(Response), 
               size = NumWebVisitsMonth)) +
  geom_point(alpha = 0.6) +
  scale_size_continuous(range = c(2, 10)) +
  labs(title = "Customer Value Analysis: Recency vs Spending",
       color = "Campaign Response",
       size = "Web Visits") +
  theme_minimal()

# 3. Purchase Channel Analysis
library(tidyr)
channel_data <- data %>%
  summarise(across(c(NumWebPurchases, NumCatalogPurchases, NumStorePurchases), sum)) %>%
  pivot_longer(everything(), names_to = "Channel", values_to = "Count")

ggplot(channel_data, aes(x = "", y = Count, fill = Channel)) +
  geom_bar(stat = "identity", width = 1) +
  coord_polar("y", start = 0) +
  theme_minimal() +
  labs(title = "Distribution of Purchase Channels")


# 8. Customer Complaints Analysis
complaints_data <- data %>%
  group_by(Education, Marital_Status) %>%
  summarise(ComplaintRate = mean(Complain)) %>%
  ungroup()

ggplot(complaints_data, aes(x = Education, y = ComplaintRate, fill = Marital_Status)) +
  geom_bar(stat = "identity", position = "dodge") +
  theme_minimal() +
  labs(title = "Complaint Rate by Customer Segment")



# label encoding 
table(data$Education)
data$Education <- as.numeric(factor(data$Education))
table(data$Marital_Status)
data$Marital_Status <- as.numeric(factor(data$Marital_Status))

library(corrplot)
library(RColorBrewer)
M <-cor(data)

corrplot(M, type="upper", order="hclust",title="Correlation Plot",
         col=brewer.pal(n=5, name="RdYlBu"))



data$Response<-as.factor(data$Response)




#splitting the data into train and test
set.seed(681)
index<-sample(2,nrow(data),replace=T,prob=c(0.7,0.3))
train<-data[index==1,]
head(train)
test<-data[index==2,]
head(test)

# logistic regression
#training data
model<-glm(Response~.,train,family='binomial')
summary(model)
#predictions for training data
p1<-predict(model,train,type='response')
pred1<-ifelse(p1>0.5,1,0)
# Predictions for test data
p2<-predict(model,test,type='response')
pred2<-ifelse(p2>0.5,1,0)

#accuracy metrics for train and test
cat('confusion matrix for train data\n')
cf_matrix_train<-table(predicted=pred1,actual=train$Response)
cf_matrix_train
# Model Performance metrics for training data
tn<-cf_matrix_train[1,1]
fn<-cf_matrix_train[1,2]
fp<-cf_matrix_train[2,1]
tp<-cf_matrix_train[2,2]

cat('Model Performance metrics for train data\n')
train_accuracy<-(tp+tn)/(tp+tn+fp+fn)
cat('\nAccuracy ',train_accuracy)
train_precision<-tp/(tp+fp)
cat('\nPrecision ',train_precision)

train_sensivity<-tp/(tp+fn)
cat('\nSensivity ',train_sensivity)

train_specificity<-tn/(tn+fp)
cat('\nSpecificity ',train_specificity)

train_misclassification_rate<-1-train_accuracy
cat('\nMisclassification rate for train data ',train_misclassification_rate)

#AUC Curve for logistic regression
library(pROC)
roc_curve<-roc(test$Response,p2)
plot(roc_curve)
auc_value <- auc(roc_curve)
print(auc_value)

# decision tree
library(caret)
library(rpart)
library(rpart.plot)

#train
tree<-rpart(Response~.,train,cp=0.01, method = "class")
rpart.plot(tree)
printcp(tree)
plotcp(tree)
#confusion matrix for train data
p<-predict(tree,train,type='class')
confusionMatrix(p,train$Response,positive = '1')
#confusion matrix for test data
p2<-predict(tree,test,type='class')
confusionMatrix(p2,test$Response,positive = '1')
# ROC Curve and AUC for the test set
library(pROC)
p <- predict(tree, test, type = 'prob')
p <- p[, 1] 

roc_obj <- roc(test$Response, p, percent = TRUE)
plot.roc(roc_obj,
         print.auc = TRUE, 
         auc.polygon = TRUE, 
         grid = c(0.1, 0.2),
         grid.col = c("green", "red"), 
         max.auc.polygon = TRUE,
         auc.polygon.col = "lightblue", 
         print.thres = TRUE, 
         main = 'ROC Curve for Response')

#random forest

library(caret)
library(randomForest)

set.seed(555)
cvcontrol <- trainControl(method="repeatedcv", 
                          number = 5,
                          repeats = 1,
                          allowParallel=TRUE)


set.seed(555)
random_forest<-train(Response~.,data=train, method="rf",trControl=cvcontrol,importance=TRUE)
plot(varImp(random_forest))
p <- predict(random_forest, test, type = 'raw')
confusionMatrix(p, test$Response,positive = '1')


library(ROSE)
balanced_data<-ROSE(Response~., data, seed=1)$data
table(balanced_data$Response)

set.seed(555)
index<-sample(2,nrow(balanced_data),replace=T,prob=c(0.7,0.3))
train_b<-balanced_data[index==1,]
test_b<-balanced_data[index==2,]

library(caret)
library(randomForest)

set.seed(555)
cvcontrol <- trainControl(method="repeatedcv", 
                          number = 5,
                          repeats = 1,
                          allowParallel=TRUE)


set.seed(555)

random_forest<-train(Response~.,data=train_b, method="rf",trControl=cvcontrol,importance=TRUE)
plot(varImp(random_forest))
p <- predict(random_forest, test_b, type = 'raw')
confusionMatrix(p, test_b$Response,positive = '1')


#xgboost 


set.seed(555) 
cvcontrol <- trainControl(method="repeatedcv", 
                          number = 5,
                          repeats = 1,
                          allowParallel=TRUE)
set.seed(555)
boosting <- train(Response ~ ., 
                  data=train,
                  method="xgbTree",   
                  trControl=cvcontrol,
                  tuneGrid = expand.grid(nrounds = 500,
                                         max_depth = 4,
                                         eta = 0.28,
                                         gamma = 1.8,
                                         colsample_bytree = 1,
                                         min_child_weight = 1,
                                         subsample = 1))
plot(varImp(boosting))
p <- predict(boosting, test, type = 'raw')
confusionMatrix(p, test$Response,positive = '1')


results<-c(89,90,88,88,88)
barplot(results)
