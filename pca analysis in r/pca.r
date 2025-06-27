
library(ISLR)
library(ggplot2)

data(NCI60)


labs <- NCI60$labs
df <- NCI60$data
str(df)

set.seed(123)


print(dim(df))

selected_vars <- sample(ncol(df), 10, replace = FALSE)
selected_vars

sampled_data <- df[, selected_vars]
sampled_data

# Step 4: Perform PCA on the reduced dataset
pca_ <- prcomp(sampled_data, scale. = TRUE)
pca_

summary(pca_)
attributes(pca_)

plot(pca_, type = "lines")
library(psych)
pairs.panels(pca_$x, 
             gap = 0,
             pch = 21)

library(ggbiplot)
# Bi-Plot using ggbiplot
g <- ggbiplot(pca_, 
              obs.scale = 1, 
              var.scale = 1,
              ellipse = TRUE, 
              circle = TRUE)
g <- g + theme(legend.direction = 'horizontal', 
               legend.position = 'top')

print(g)
biplot(pca_, col = c("gray", "blue"), cex = c(0.5, 0.5))



library(psych)
pairs.panels(sampled_data,  # Exclude the 'score' column
             gap = 0,
             pch = 21)
