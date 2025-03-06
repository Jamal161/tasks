<template>
    <div class="header">
        <h2>Task List</h2>
        <button @click="logout" class="logout">Logout</button>
    </div>

    <div class="add-task-form">
        <form @submit.prevent="createTask">
            <input v-model="newTask.title" placeholder="Task Title" required />
            <textarea v-model="newTask.description" placeholder="Task Description" required></textarea>
            <button type="submit">Add Task</button>
        </form>
    </div>

    <div class="task-list">
        <table style="width: 700px;">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Task Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in tasks" :key="task.id">
                    <td v-if="editingTaskId !== task.id" > <span :class="{ completed: task.completed }">{{ task.title }}</span></td>
                    <td v-else><input v-model="editedTask.title" /></td>

                    <td v-if="editingTaskId !== task.id"> <span :class="{ completed: task.completed }">{{ task.description }}</span></td>
                    <td v-else><textarea v-model="editedTask.description"></textarea></td>

                    
                    <td>
                        <button @click="toggleComplete(task)">✔</button>
                        <button @click="editTask(task)" v-if="editingTaskId !== task.id">✏ Edit</button>
                        <button @click="saveTask(task.id)" v-if="editingTaskId === task.id">💾 Save</button>
                        <button @click="deleteTask(task.id)">🗑 Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import { useAuthStore } from '@/store/auth';

const tasks = ref([]);
const newTask = ref({ title: '', description: '' });
const editingTaskId = ref(null);
const editedTask = ref({ title: '', description: '' });

const { logout } = useAuthStore();

const fetchTasks = async () => {
    try {
        const { data } = await api.get('/tasks', {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        tasks.value = data;
    } catch (error) {
        console.error('Failed to fetch tasks:', error.response ? error.response.data : error);
    }
};

const createTask = async () => {
    try {
        const { data } = await api.post('/tasks', newTask.value, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        tasks.value.push(data);
        newTask.value = { title: '', description: '' };
    } catch (error) {
        console.error('Failed to create task:', error.response ? error.response.data : error);
    }
};

const editTask = (task) => {
    editingTaskId.value = task.id;
    editedTask.value = { title: task.title, description: task.description };
};

const saveTask = async (id) => {
    try {
        await api.put(`/tasks/${id}`, editedTask.value, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        const index = tasks.value.findIndex(task => task.id === id);
        tasks.value[index] = { ...tasks.value[index], ...editedTask.value };
        editingTaskId.value = null;
    } catch (error) {
        console.error('Failed to update task:', error.response ? error.response.data : error);
    }
};

const toggleComplete = async (task) => {
    await api.put(`/tasks/${task.id}`, { completed: !task.completed }, {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    task.completed = !task.completed;
};

const deleteTask = async (id) => {
    try {
        await api.delete(`/tasks/${id}`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        tasks.value = tasks.value.filter(task => task.id !== id);
    } catch (error) {
        console.error('Failed to delete task:', error.response ? error.response.data : error);
    }
};

onMounted(fetchTasks);
</script>

<style scoped>

.completed { text-decoration: line-through; }

.error {
  color: red;
}
table {
  border-collapse: collapse;
  margin-top: 20px;
}
th, td {
  padding: 10px;
  border: 1px solid #ddd;
  text-align: left;
}
th {
  background-color: #f4f4f4;
}
button {
  margin: 5px;
}

.add-task-form {
  background: #f9f9f9; 
  border: 1px solid #e0e0e0; 
  border-radius: 8px; 
  padding: 15px; 
  margin-top: 20px; 
  width: 500px;
}

.add-task-form input,
.add-task-form textarea {
  width: 90%; 
  margin: 10px 0; 
  padding: 10px; 
  border: 1px solid #ccc; 
  border-radius: 4px; 
}

.header {
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
  margin-bottom: 20px; 
}

.logout {
  background-color: #dc3545; 
  color: white;
  border: none;
  border-radius: 4px;
  padding: 10px 15px;
  cursor: pointer;
}

.logout:hover {
  background-color: #c82333; 
}

button {
  background-color: #28a745; 
  color: white;
  border: none;
  border-radius: 4px;
  padding: 10px;
  cursor: pointer;
  margin-right: 5px; 
}

button:hover {
  background-color: #218838; 
}

.delete-task {
  background-color: #dc3545; 
  color: white;
  border: none;
  border-radius: 4px;
  padding: 10px;
  cursor: pointer;
}

.delete-task:hover {
  background-color: #c82333; 
}

.completed {
  text-decoration: line-through;
  color: #999;
}
</style>
