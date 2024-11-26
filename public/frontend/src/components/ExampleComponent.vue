<template>
  <div>
    <h1>Plan List</h1>
    <table class="data-table">
      <thead>
      <tr>
        <th>ID</th>
        <th>External ID</th>
        <th>GUID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Price</th>
        <th>Type</th>
        <th>Category</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="plan in plans" :key="plan.id">
        <td>{{ plan.id }}</td>
        <td>{{ plan.external_id }}</td>
        <td>{{ plan.guid }}</td>
        <td>{{ plan.name }}</td>
        <td>{{ plan.status }}</td>
        <td>{{ plan.price }}</td>
        <td>{{ plan.type }}</td>
        <td>{{ plan.category_name || 'N/A' }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    plans: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style scoped>
.data-table {
  width: 100%;
  border-collapse: collapse;
}
.data-table th,
.data-table td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: left;
}
</style>
