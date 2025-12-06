# 🌐 Tech Navigator  
### A Developer-Focused Technology & Stack Decision Platform

Tech Navigator is a web-based platform designed to help developers, students, and learners quickly discover the best programming languages, frameworks, tools, and technologies for any development domain — such as full-stack, backend, frontend, systems programming, mobile, and more.

Rather than getting confused by thousands of options, users can easily explore technologies filtered by:
- Performance  
- Ease of learning  
- Popularity  
- Difficulty level  
- System-level capability  
- Ecosystem strength  
- Cross-platform features  
- Best use cases  

The platform is built using **HTML, CSS, JavaScript (Frontend)** and **PHP + MySQL (Backend)**.

---

# 🚀 Features

### ✔ Domain-based exploration  
Browse categories such as:
- Full Stack Development  
- Backend Development  
- Frontend Development  
- Systems Programming  
- AI/ML  
- Mobile App Development  
- Desktop Development  

### ✔ Technology listings  
Each domain includes:
- Programming Languages  
- Frameworks  
- Tools  
- Tags (filters)

### ✔ Smart Filters  
Users can filter items based on:
- Performance Score  
- Popularity Score  
- Difficulty Level  
- Learning Curve  
- Tags (e.g., backend, secure, high-performance)

### ✔ Item Details  
Each item shows:
- Description  
- Pros & Cons  
- Difficulty & performance scores  
- Developer/company  
- Release year  
- Official site link  

### ✔ Comparison Feature  
Compare two technologies instantly:
- React vs Angular  
- Django vs Laravel  
- Python vs Node.js  
and more.

### ✔ Scalable Architecture  
Clean separation of:
- Frontend  
- Backend APIs  
- Database  
- UI Components  

---

# 🏗️ Project Architecture

## **Frontend**
- HTML  
- CSS (modular structure)  
- JavaScript (AJAX + UI components)

## **Backend**
- PHP (API endpoints)  
- MySQL (data storage)

## **Key Features**
- API-based data fetching  
- Reusable UI components  
- Flexible category/item system  
- Extendable tagging system  

---

# 📁 Folder Structure

tech-navigator/
│
├── index.php
├── categories.php
├── items.php
├── compare.php
│
├── assets/
│ ├── css/
│ │ ├── main.css
│ │ ├── responsive.css
│ │ └── components/
│ │ ├── cards.css
│ │ ├── navbar.css
│ │ ├── filters.css
│ │ └── modal.css
│ ├── js/
│ │ ├── app.js
│ │ ├── filters.js
│ │ ├── search.js
│ │ ├── compare.js
│ │ ├── ajax/
│ │ │ ├── fetchCategories.js
│ │ │ ├── fetchItems.js
│ │ │ └── fetchFilters.js
│ │ └── ui/
│ │ ├── ui-cards.js
│ │ ├── ui-modal.js
│ │ └── ui-navbar.js
│ ├── images/
│ └── icons/
│
├── backend/
│ ├── db/
│ │ ├── connection.php
│ │ └── seed.sql
│ ├── api/
│ │ ├── getCategories.php
│ │ ├── getItems.php
│ │ ├── getItemDetails.php
│ │ ├── getFrameworks.php
│ │ ├── getTools.php
│ │ └── compareItems.php
│ └── utils/
│ ├── sanitize.php
│ ├── validator.php
│ └── helpers.php
│
├── config/
│ ├── config.php
│ └── routes.php
│
├── views/
│ ├── components/
│ │ ├── header.php
│ │ ├── footer.php
│ │ ├── card.php
│ │ ├── filter-bar.php
│ │ └── navbar.php
│ ├── home.php
│ ├── category-view.php
│ ├── item-view.php
│ └── compare-view.php
│
└── README.md


---

# 🗄️ Database Schema

### **Tables**
- `categories` – Main development domains  
- `items` – Languages, frameworks, tools  
- `tags` – Filtering tags  
- `item_tags` – Many-to-many mapping  
- `comparisons` (optional) – Store compare data  

The complete SQL file is included in:  
`backend/db/seed.sql`

---

# 🛠️ Installation & Setup

### **1. Clone the Repository**
```bash
git clone https://github.com/pawangujar/tech-navigator.git
