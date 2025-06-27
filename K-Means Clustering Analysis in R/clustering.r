data<- read.csv('/Users/adithyaprahasith/Downloads/Pharmaceuticals.csv')
head(data)
View(data)
str(data)
summary(data)



# Normalize 
z <- data[,-c(1,2,12,13,14)]
means <- apply(z,2,mean)
sds <- apply(z,2,sd)
nor <- scale(z,center=means,scale=sds)
nor
# Calculate distance matrix  
distance = dist(nor)
distance
# Hierarchical agglomerative clustering  
mydata.hclust = hclust(distance)
plot(mydata.hclust)
plot(mydata.hclust,labels=data$Symbol,main='Default from hclust')
plot(mydata.hclust,hang=-1)

# Hierarchical agglomerative clustering using "average" linkage 
mydata.hclust<-hclust(distance,method="average")
plot(mydata.hclust,hang=-1)

# Cluster membership
member = cutree(mydata.hclust,3)
table(member)

# Characterizing clusters 
aggregate(nor,list(member),mean)
aggregate(data[,-c(1,2,12,13,14)],list(member),mean)

# Silhouette Plot
library(cluster) 
plot(silhouette(cutree(mydata.hclust,3), distance)) 

# Scree Plot
wss <- (nrow(nor)-1)*sum(apply(nor,2,var))
for (i in 2:20) wss[i] <- sum(kmeans(nor, centers=i)$withinss)
plot(1:20, wss, type="b", xlab="Number of Clusters", ylab="Within groups sum of squares") 

# K-means clustering
set.seed(123)
kc<-kmeans(nor,3)
kc
companies_cluster <- data.frame(Company=data$Symbol, Cluster_ = kc$cluster)
companies_cluster
