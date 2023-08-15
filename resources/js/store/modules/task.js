export const actions = {
    async saveTask({
        commit
    }, payload) {
        return await TaskAxios.saveTask(payload);
    },
    async getTasks({
        commit
    }, payload) {
        return await TaskAxios.getTasks();
    },
    async updateTask({
        commit
    }, payload) {
        return await TaskAxios.updateTask(payload);
    },
    async deleteTask({
        commit
    }, payload) {
        return await TaskAxios.deleteTask(payload);
    },
};
let TaskAxios = class {

    static saveTask(payload) {
        return axios.post(api.path('task.saveTask'), payload)
            .then(resp => {
                return {
                    status: 200,
                    data: resp.data
                };
            })
            .catch(err => {
                return {
                    status: 400,
                    data: err.response.data
                };
            });
    }
    static getTasks() {
        return axios.get(api.path('task.getTasks'))
            .then(resp => {
                return resp.data.details;
            })
            .catch(err => {
                return {
                    status: 400,
                    data: err.response.data
                };
            });
    }
    static updateTask(payload) {
        return axios.post(api.path('task.updateTask'), payload)
            .then(resp => {
                return {
                    status: 200,
                    data: resp.data
                };
            })
            .catch(err => {
                return {
                    status: 400,
                    data: err.response.data
                };
            });
    }
    static deleteTask(payload) {
        return axios.post(api.path('task.deleteTask'), payload)
            .then(resp => {
                return {
                    status: 200,
                    data: resp.data
                };
            })
            .catch(err => {
                return {
                    status: 400,
                    data: err.response.data
                };
            });
    }
};