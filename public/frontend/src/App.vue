<template>
  <div id="app" class="app-container">
    <!-- Header Section -->
    <div class="header">
      <h1>Plan Management</h1>
      <div class="stats">
        <p>Active Plans: <span class="highlight">{{ activeCount }}</span></p>
        <p>Inactive Plans: <span class="highlight">{{ inactiveCount }}</span></p>
      </div>
    </div>

    <!-- Filters -->
    <div class="filters">
      <select v-model="filterCategory" class="filter-select">
        <option value="">Filter by Category</option>
        <option v-for="category in categories" :key="category.id" :value="category.id">
          {{ category.name }}
        </option>
      </select>
      <select v-model="filterStatus" class="filter-select">
        <option value="">Filter by Status</option>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
      </select>
      <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by name"
          class="search-input"
      />
    </div>

    <!-- Data Table -->
    <table v-if="filteredPlans.length" class="data-table">
      <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Price</th>
        <th>Price Without MRP</th>
        <th>MRP Amount</th>
        <th>Type</th>
        <th>Category</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="plan in paginatedPlans" :key="plan.id">
        <td>{{ plan.id }}</td>
        <td>{{ plan.name }}</td>
        <td>{{ plan.status }}</td>
        <td>{{ plan.price }}</td>
        <td>{{ plan.price_without_mrp }}</td>
        <td>{{ plan.mrp_amount }}</td>
        <td>{{ plan.type }}</td>
        <td>{{ plan.category_name || "Unknown" }}</td>
      </tr>
      </tbody>
    </table>
    <p v-else class="no-data">No plans available.</p>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="pagination">
      <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="pagination-button">
        Previous
      </button>
      <span>Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="pagination-button">
        Next
      </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      plans: [],
      categories: [],
      filterCategory: "",
      filterStatus: "",
      searchQuery: "",
      currentPage: 1,
      itemsPerPage: 5,
      totalPages: 1,
    };
  },
  computed: {
    activeCount() {
      return this.plans.filter(plan => plan.status === "Active").length;
    },
    inactiveCount() {
      return this.plans.filter(plan => plan.status === "Inactive").length;
    },
    filteredPlans() {
      return this.plans.filter(plan => {
        const matchesCategory =
            !this.filterCategory || plan.category_id === parseInt(this.filterCategory);
        const matchesStatus =
            !this.filterStatus || plan.status === this.filterStatus;
        const matchesQuery =
            !this.searchQuery ||
            plan.name.toLowerCase().includes(this.searchQuery.toLowerCase());
        return matchesCategory && matchesStatus && matchesQuery;
      });
    },
    paginatedPlans() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredPlans.slice(start, end);
    },
  },
  mounted() {
    if (window.PHP_PLANS && Array.isArray(window.PHP_PLANS)) {
      this.plans = window.PHP_PLANS.map(plan => {
        const category = window.PHP_CATEGORIES.find(cat => cat.id === plan.category_id);
        return {
          ...plan,
          category_name: category ? category.name : "Unknown",
        };
      });
    }
    if (window.PHP_CATEGORIES && Array.isArray(window.PHP_CATEGORIES)) {
      this.categories = window.PHP_CATEGORIES;
    }
  },
  methods: {
    changePage(page) {
      if (page > 0 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
  },
};
</script>

<style scoped>
/* Main Container */
.app-container {
  font-family: Arial, sans-serif;
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
  color: #333;
}

/* Header Section */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}
.header h1 {
  font-size: 28px;
  color: #4caf50;
  margin: 0;
}
.stats p {
  margin: 0;
  font-size: 16px;
}
.stats .highlight {
  font-weight: bold;
  color: #4caf50;
}

/* Filters */
.filters {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}
.filter-select,
.search-input {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
}
.search-input {
  flex: 1;
}

/* Data Table */
.data-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}
.data-table th,
.data-table td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
  font-size: 14px;
}
.data-table th {
  background-color: #f4f4f4;
  color: #555;
  text-transform: uppercase;
}
.data-table tr:nth-child(even) {
  background-color: #f9f9f9;
}
.data-table tr:hover {
  background-color: #f1f1f1;
}
.no-data {
  text-align: center;
  font-size: 18px;
  color: #888;
  margin-top: 20px;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}
.pagination-button {
  padding: 8px 15px;
  border: 1px solid #ccc;
  background-color: #f5f5f5;
  cursor: pointer;
  border-radius: 5px;
  font-size: 14px;
}
.pagination-button:hover {
  background-color: #e0e0e0;
}
.pagination-button:disabled {
  background-color: #e0e0e0;
  cursor: not-allowed;
}
</style>
