<template>
  <div class="p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold">Staff List</h2>
      <button class="bg-blue-600 text-white px-4 py-2 rounded" @click="openAddDialog">Add Staff</button>
    </div>

    <div v-if="isLoading">Loading...</div>
    <div v-else-if="isError" class="text-red-600">Failed to load staff</div>

    <table v-else class="table-auto w-full border border-gray-300">
      <thead class="bg-gray-100">
      <tr>
        <th class="border px-4 py-2">ID</th>
        <th class="border px-4 py-2">Name</th>
        <th class="border px-4 py-2">Email</th>
        <th class="border px-4 py-2">Phone</th>
        <th class="border px-4 py-2">Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="staff in data" :key="staff.id">
        <td class="border px-4 py-2">{{ staff.id }}</td>
        <td class="border px-4 py-2">{{ staff.name }}</td>
        <td class="border px-4 py-2">{{ staff.email }}</td>
        <td class="border px-4 py-2">{{ staff.phone }}</td>
        <td class="border px-4 py-2 space-x-2">
          <button class="text-blue-600" @click="openEditDialog(staff)">Edit</button>
          <button class="text-red-600" @click="openDeleteDialog(staff)">Delete</button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Dialog Form -->
    <div v-if="showFormDialog" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-6 rounded w-[400px]">
        <h3 class="text-lg font-bold mb-4">{{ isEditMode ? 'Edit' : 'Add' }} Staff</h3>
        <input v-model="form.name" type="text" placeholder="Name" class="w-full mb-2 border px-3 py-2" />
        <input v-model="form.email" type="email" placeholder="Email" class="w-full mb-2 border px-3 py-2" />
        <input v-model="form.phone" type="text" placeholder="Phone" class="w-full mb-4 border px-3 py-2" />

        <div class="flex justify-end gap-2">
          <button @click="closeDialog" class="text-gray-600">Cancel</button>
          <button @click="submitForm" class="bg-blue-600 text-white px-4 py-1 rounded">
            {{ isEditMode ? 'Update' : 'Create' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation -->
    <div v-if="showDeleteDialog" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-4 rounded w-[300px]">
        <p class="mb-4">Are you sure you want to delete <strong>{{ form.name }}</strong>?</p>
        <div class="flex justify-end gap-2">
          <button @click="closeDialog" class="text-gray-600">Cancel</button>
          <button @click="confirmDelete" class="bg-red-600 text-white px-4 py-1 rounded">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useStaffQuery } from '../composables/useStaffQuery'
import { useCreateStaff } from '../composables/useCreateStaff'
import { useUpdateStaff } from '../composables/useUpdateStaff'
import { useDeleteStaff } from '../composables/useDeleteStaff'
import { watch } from 'vue'

const { data, isLoading, isError } = useStaffQuery()
const createStaff = useCreateStaff()
const updateStaff = useUpdateStaff()
const deleteStaff = useDeleteStaff()

const showFormDialog = ref(false)
const showDeleteDialog = ref(false)
const isEditMode = ref(false)
const form = ref({ id: null, name: '', email: '', phone: '' })

const openAddDialog = () => {
  isEditMode.value = false
  form.value = { id: null, name: '', email: '', phone: '' }
  showFormDialog.value = true
}

const openEditDialog = (staff) => {
  isEditMode.value = true
  form.value = { ...staff }
  showFormDialog.value = true
}

const openDeleteDialog = (staff) => {
  form.value = { ...staff }
  showDeleteDialog.value = true
}

const closeDialog = () => {
  showFormDialog.value = false
  showDeleteDialog.value = false
}

const submitForm = async () => {
  try {
    if (isEditMode.value) {
      await updateStaff.mutateAsync(form.value)
    } else {
      await createStaff.mutateAsync(form.value)
    }
    closeDialog()
  } catch (err) {
    alert('Failed to save staff.')
  }
}

const confirmDelete = async () => {
  try {
    await deleteStaff.mutateAsync(form.value.id)
    closeDialog()
  } catch (err) {
    alert('Failed to delete staff.')
  }
}

watch(() => form.value.name, (newName) => {
  if (newName && newName.length > 10) {
    alert('Tên không được dài quá 10 ký tự')
  }
})


</script>
