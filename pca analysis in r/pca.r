library (ISLR)
labs <- NCI60$labs
df <- NCI60$data
dim(df)
table(labs)


scaled_df <- scale(df)
pca_<- prcomp(scaled_df, center = TRUE, scale. = TRUE)
attributes(pca_)
print(pca_)
summary(pca_)

plot(pca_$x[, 1:2], col = as.factor(NCI60$labs), pch = 19,
     xlab = "PC1", ylab = "PC2")
legend("topright", legend = unique(NCI60$labs), col = 1:length(unique(NCI60$labs)), pch = 19)

plot(pca_, type = "l")



attributes(pca_)
print(pca_)
summary(pca_)
plot(pca_, type = "lines")

# Orthogonality of PCs
pairs.panels(pca_$x, 
             gap=0,
             pch=21)
# Bi-Plot
library(ggbiplot)
g <- ggbiplot(pca_, 
              obs.scale = 1, 
              var.scale = 1, 
              ellipse = TRUE, 
              circle = TRUE,
              ellipse.prob = 0.68)
g <- g + scale_color_discrete(name = '')
g <- g + theme(legend.direction = 'horizontal', 
               legend.position = 'top')
print(g)


# Create the biplot using base R
biplot(pca_, col = c("gray", "blue"), cex = c(0.5, 0.5))


# Generate a scree plot to visualize the variance explained by each PC
plot(pca_$sdev^2 / sum(pca_$sdev^2), type = "b", 
     xlab = "Principal Component", ylab = "Proportion of Variance Explained",
     main = "Scree Plot")



set.seed(123)  # Set a seed for reproducibility
sample_indices <- sample(1:nrow(NCI60$data), 30)  # Choose 20 random rows
sampled_data <- NCI60$data[sample_indices, ]
sampled_labels <- NCI60$labs[sample_indices]
sampled_scaled_data <- scale(sampled_data)
sampled_pca_result <- prcomp(sampled_scaled_data, center = TRUE, scale. = TRUE)
# Scree plot for sampled data
summary(sampled_pca_result)
plot(sampled_pca_result$sdev^2 / sum(sampled_pca_result$sdev^2), type = "b",
     xlab = "Principal Component", ylab = "Proportion of Variance Explained",
     main = "Scree Plot - Sampled Data")


# Scree plot to visualize the proportion of variance explained by each PC
plot(pca_$sdev^2 / sum(pca_$sdev^2), type = "b", 
     xlab = "Principal Component", ylab = "Proportion of Variance Explained",
     main = "Scree Plot", pch = 19, col = "blue")

# Calculate cumulative variance explained
cum_var <- cumsum(pca_$sdev^2) / sum(pca_$sdev^2)

# Plot cumulative variance explained
plot(cum_var, type = "b", xlab = "Number of Principal Components", 
     ylab = "Cumulative Variance Explained", main = "Cumulative Variance Plot", 
     pch = 19, col = "red")

# Add a horizontal line to mark 80% variance explained
abline(h = 0.8, col = "darkgreen", lty = 2)

# Assuming 'NCI60$labs' contains group labels (like cancer types)
library(ggfortify)
autoplot(pca_, data = data.frame(NCI60$labs), colour = 'NCI60.labs', 
         loadings = TRUE, loadings.label = TRUE, main = "PCA Biplot with Labels")

biplot(pca_, scale = 1, cex = 0.6)
# Adjust x and y axis limits if needed


# Assuming `data_matrix` is your original dataset (genes in columns, samples in rows)

# Set a seed for reproducibility
set.seed(123)

# Randomly select a smaller subset of 50 variables (genes)
selected_vars <- sample(ncol(df), 50, replace = FALSE)

# Create a new dataset with only these selected variables
sampled_data <- df[, selected_vars]

# Perform PCA on the reduced dataset
pca_sampled <- prcomp(sampled_data, scale. = TRUE)
summary(pca_sampled)
# Create a biplot for the sampled PCA
biplot(pca_sampled, scale = 0, cex = 0.6, main = "PCA Biplot (Sampled Variables)")

