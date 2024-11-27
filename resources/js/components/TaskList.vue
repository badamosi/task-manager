<template>
    <div class="container mt-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Task List</h3>
        <button class="btn btn-primary" @click="showTaskForm">Add Task</button>
      </div>
  
      <table class="table table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Task Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(task, index) in tasks.data" :key="`task-${task.id}`">
            <td>{{ index + 1 }}</td>
            <td>{{ task.title }}</td>
            <td>{{ task.description }}</td>
            <td>
              <span :class="`badge bg-${task.status === 'pending' ? 'warning' : task.status === 'completed' ? 'success' : 'primary'} text-dark`">{{ task.status }}</span>
            </td>
            <td>
              <button class="btn btn-sm btn-info" @click="editTask(task)">Edit</button>
              <button class="btn btn-sm btn-danger" @click="deleteTask(task.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="box-footer clearfix" v-if="tasks.total > tasks.per_page">
        <nav aria-label="Page navigation example"
            class="mt-4 position-absolute b-10 mx-auto" >
            <paginate
                :page-count="tasks.last_page"
                :click-handler="loadTasks"
                :page-range="3"
                :prev-text="'Prev'"
                :next-text="'Next'"
                :container-class="'pagination pagination-space justify-content-center'"
                :page-link-class="'page-link'"
                :prev-link-class="'page-link'"
                :next-link-class="'page-link'"
                :page-class="'page-item'">
            </paginate>
        </nav>
      </div>
  
      <!-- Modal for Task Form -->
       <TaskFormModal :selected="selectedTask" @update="syncUpdatedTask" @new="syncNewTask"/>
    </div>
  </template>
  
  <script>
  import TaskFormModal from './TaskFormModal.vue';

  export default {
    components: { TaskFormModal },
    data() {
      return {
        tasks: [],
        selectedTask: null,
        loading: false,
      };
    },
    mounted(){
      this.loadTasks()
    },
    methods: {
      showTaskForm() {
        $('#taskModal').modal('show');
      },
      editTask(task) {
        this.selectedTask = task;

        $('#taskModal').modal('show');
      },
      async deleteTask(id){
        const isConfirmed = confirm("Are you sure you want to delete this task?");
    
        if (isConfirmed) {
          await this.axios.delete(`/api/task/${id}`)
          .then(res => {
              this.tasks.data = this.tasks.data.filter(itm => itm.id != id)
              alert('🎉 Successfully deleted task.')
          }).catch(err => alert('something went wrong'))
        }
        else {
          return false;
        }
      },

      async loadTasks(page =1) {
        this.loading = true;
        await this.axios
          .get(`/api/task?page=${page}`)
          .then((res) => {
            this.tasks = res.data;
            console.log(res)
            this.loading = false;
          })
          .catch(err => console.error(err))
      },

      syncNewTask(payload){
          this.tasks.data.unshift(payload)
      },

      syncUpdatedTask(payload){
        const { data:tasks } = this.tasks
        const index = tasks.findIndex(itm => itm.id === payload.id)
        
        this.$set(this.tasks.data, index, payload);
      },
     
    },
  };
  </script>
  
  