// TaskViewModel.js - O cérebro do Front-end

class TaskViewModel {
    constructor() {
        this.tasks = []; // O "Estado" da aplicação (Model no lado do cliente)
        this.init();
    }

    async init() {
        // Mapeia elementos da View
        this.listElement = document.getElementById('taskList');
        this.inputElement = document.getElementById('taskTitle');
        this.dateElement = document.getElementById('taskDate');
        this.addBtn = document.getElementById('addBtn');

        // Eventos da View que disparam ações na ViewModel
        this.addBtn.onclick = () => this.addTask();

        // Busca dados iniciais
        await this.fetchTasks();
    }

    // Busca dados da API PHP
    async fetchTasks() {
        const response = await fetch('api.php?action=list');
        this.tasks = await response.json();
        this.render(); // Atualiza a View (Data Binding)
    }

    async addTask() {

        const title = this.inputElement.value;
        if (!title) {
            return alert('Digite algo!');
        }

        const dueDate = this.dateElement.value;
        if (!dueDate) {
            return alert('Escolha uma data!');
        }

        await fetch('api.php?action=create', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                title: title,
                description: '',
                due_date: dueDate
            })
        });

        this.inputElement.value = '';
        this.dateElement.value = '';

        await this.fetchTasks();
    }

    async deleteTask(id) {
        await fetch(`api.php?action=delete&id=${id}`);
        await this.fetchTasks();
    }

    // O "Binder": Sincroniza o array de tarefas com o HTML
    render() {
        this.listElement.innerHTML = '';
        this.tasks.forEach(task => {
            const li = document.createElement('li');
            li.className = task.done ? 'done' : '';
            li.innerHTML = `
                <span>${task.title} - ${task.due_date}</span>
                <button onclick="vm.deleteTask(${task.id})">❌</button>
            `;
            this.listElement.appendChild(li);
        });
    }
}

// Instancia a ViewModel
const vm = new TaskViewModel();
