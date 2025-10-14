<template>
  <div class="card" :class="`border-left-${variant}`">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-uppercase mb-1" :class="`text-${variant}`">{{ title }}</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ value }}</div>
        </div>
        <div class="col-auto">
          <i class="fas fa-2x text-gray-300" :class="icon"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    required: true
  },
  icon: {
    type: String,
    required: true
  },
  variant: {
    type: String,
    default: 'primary'
  }
});
</script>

<style scoped>
.card {
  border-left-width: 0.25rem;
}
.border-left-primary {
  border-left-color: #4e73df;
}
.text-primary {
  color: #4e73df !important;
}
.border-left-success {
  border-left-color: #1cc88a;
}
.text-success {
  color: #1cc88a !important;
}
.border-left-info {
  border-left-color: #36b9cc;
}
.text-info {
  color: #36b9cc !important;
}
.border-left-warning {
  border-left-color: #f6c23e;
}
.text-warning {
  color: #f6c23e !important;
}
.border-left-danger {
  border-left-color: #e74a3b;
}
.text-danger {
  color: #e74a3b !important;
}
.text-gray-300 {
  color: #dddfeb !important;
}
.text-gray-800 {
  color: #5a5c69 !important;
}
.font-weight-bold {
  font-weight: 700 !important;
}
.text-xs {
  font-size: .7rem;
}
.text-uppercase {
  text-transform: uppercase !important;
}
</style>
