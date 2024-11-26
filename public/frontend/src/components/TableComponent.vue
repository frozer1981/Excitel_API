<template>
  <table class="data-table">
    <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in data" :key="item.id">
      <td>{{ item.id }}</td>
      <td>{{ item.name }}</td>
      <td>{{ item.status }}</td>
    </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: {
    data: {
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
