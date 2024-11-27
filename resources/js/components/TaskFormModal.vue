<template>
  <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="taskModalLabel">{{ editingForm ? 'Edit Task' : 'Add Task' }}</h5>
          <button type="button" class="btn-close"  aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="handleSubmit">
            <!-- Task Name -->
            <div class="mb-3">
              <label for="taskName" class="form-label">Task Name</label>
              <input
                type="text"
                class="form-control"
                id="taskName"
                v-model="form.title"
                :class="{'is-invalid': form.errors.title}"
                required
              />
              <div v-if="form.errors.title" class="invalid-feedback">{{ form.errors.title[0] }}</div>
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label for="taskDescription" class="form-label">Description</label>
              <textarea
                class="form-control"
                id="taskDescription"
                v-model="form.description"
                :class="{'is-invalid': form.errors.description}"
                required
              ></textarea>
              <div v-if="form.errors.description" class="invalid-feedback">{{ form.errors.description[0] }}</div>
            </div>

            <!-- Status -->
            <div class="mb-3">
              <label for="taskStatus" class="form-label">Status</label>
              <select
                class="form-select"
                id="taskStatus"
                v-model="form.status"
                :class="{'is-invalid': form.errors.status}"
                required
              >
                <option value="pending">Pending</option>
                <option value="in-progress">In Progress</option>
                <option value="completed">Completed</option>
              </select>
              <div v-if="form.errors.status" class="invalid-feedback">{{ form.errors.status[0] }}</div>
            </div>

            <!-- Due Date -->
            <div class="mb-3">
              <label for="taskDueDate" class="form-label">Due Date</label>
              <input
                type="date"
                class="form-control"
                id="taskDueDate"
                v-model="form.due_date"
                :class="{'is-invalid': form.errors.due_date}"
                required
              />
              <div v-if="form.errors.due_date" class="invalid-feedback">{{ form.errors.due_date[0] }}</div>
            </div>

            <div class="text-end">
              <button
                type="submit"
                class="btn btn-primary"
                :disabled="disableActionBtn"
              >
                {{ editingForm ? 'Save Changes' : 'Add Task' }}
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform';

export default {
  props: ['selected'],
  emits: ['new', 'update'],
  data() {
    return {
      editingForm: false,
      disableActionBtn: false,
      message: '',
      form: new Form({
        id: null,
        title: null,
        description: null,
        status: 'pending',
        due_date: null,
      }),
      errors: {},
    };
  },
  watch: {
    selected: function () {
      if (this.selected !== null) {
        this.editingForm = true;
        this.form.fill(this.selected);
      } else {
        this.form.reset();
        this.editingForm = false;
      }
    },
  },
  mounted() {
    $(`#taskModal`).on('hidden.bs.modal', () => {
      this.form.reset();
      this.form.errors = {}
    })
  },
  methods: {
   
    async handleSubmit() {
      this.disableActionBtn = true;

      const request = this.editingForm
        ? this.axios.put(`/api/task/${this.form.id}`, this.form)
        : this.axios.post('/api/task', this.form);

      try {
        const res = await request;
        this.disableActionBtn = false;
        $('#taskModal').modal('hide');

        if (this.editingForm) {
          this.message = 'Task updated successfully!';
          this.$emit('update', res.data);
        } else {
          this.message = 'Successfully created a new task!';
          this.$emit('new', res.data);
        }
        alert(this.message);
      } catch (err) {
        this.disableActionBtn = false;
        this.form.errors = err.response.data.errors;
      }
    },
  },
};
</script>

<style scoped>
.modal-content {
  padding: 20px;
}

.invalid-feedback {
  display: block;
}
</style>
