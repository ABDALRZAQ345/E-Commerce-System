
# **E-Commerce Store API - Laravel 11**

## **Overview**
An **API-only** application built with **Laravel 11** for managing e-commerce services. The application provides endpoints for store management, product listings, reviews, favorites, and advanced filtering with intelligent search using **MeiliSearch** ,payment with stripe and notifications with firebase .

---

## **Key Features**

### **1. Authentication**
- User login/logout/register .. using **Laravel Sanctum**.
- Verification codes via telegram .
- Password reset and change functionality.
- All API endpoints are secured and protected against unauthorized access.

---

### **2. Store and Product Management**
- **Store Management**:
    - Open a store.
    - Update store details such as name, description, contact methods, and location.

- **Product Management**:
    - Add products with multiple images.
    - Update  products.

---

### **3. Products and Store Listing**
- **Product Filtering**:
    - Best-selling products.
    - Highest-rated products.
    - Newly added products.
    - Suggested products for users.
    - Products by category.

- **Store Filtering**:
    - Most popular stores.
    - Highest-rated stores.
    - Newly added stores.
    - Suggested stores for users
    - stores by category

---

### **4. Reviews and Ratings**
- Add reviews to products and stores with ratings (1 to 5 stars).
- Filter reviews based on the rating (e.g., 1-star reviews, 5-star reviews).
- Display average ratings for each product or store.

---

### **5. Favorites**
- Add or remove products and stores from the favorites list.
- Retrieve the user's favorite stores and products.

---

### **6. Smart Search (MeiliSearch)**
- Perform quick and accurate searches on products and stores.
- Real-time search results powered by **MeiliSearch**.

---

### **7. User Management**
- Monitor user activity: active or inactive status.
- Track the last activity time for each user.

---

### **8. Notifications**
- Notifications for various events  via **Firebase Cloud Messaging (FCM)** .

---
### **9. Statistics**
-  total sales , new users , active users , total products , conversation rate ,category store percentage , category product percentage and more .

---

### **10. Payment **
-  payment with stripe .

---
## **Installation and Setup**

### **1. System Requirements**
- **PHP** 8.2 or later
- **Laravel** 11.x
- **Composer**
- **MeiliSearch** installed and running
- **MySQL** or any supported database

---

### **2. Installation Steps**

1. **Clone the Project**


2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
    - Copy the `.env` file:
      ```bash
      cp .env.example .env
      ```
    - Configure database, MeiliSearch settings.

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations and Seed Database**
   ```bash
   php artisan migrate --seed
   ```

6. **Run MeiliSearch**
   ```bash
   ./meilisearch
   ```

7. **Start the Server**
   ```bash
   php artisan serve
   ```

8. **Run Queues for Background Jobs (Optional)**
   ```bash
   php artisan queue:work
   ```

---
