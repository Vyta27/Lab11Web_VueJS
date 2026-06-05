const { createApp } = Vue

const apiUrl = 'http://localhost/lab11_ci/ci4/public'

createApp({
    data() {
        return {
            artikel: [],
            formData: {
                id: null,
                judul: '',
                isi: '',
                status: 0
            },
            showForm: false,
            formTitle: 'Tambah Data',
            statusOptions: [
                { text: 'Draft',   value: 0 },
                { text: 'Publish', value: 1 },
            ],
        }
    },
    mounted() {
        this.loadData()
    },
    methods: {
        loadData() {
            axios.get(apiUrl + '/post')
                .then(response => {
                    this.artikel = response.data.artikel
                })
                .catch(error => console.log(error))
        },

        tambah() {
            this.showForm  = true
            this.formTitle = 'Tambah Data'
            this.formData  = { id: null, judul: '', isi: '', status: 0 }
        },

        edit(data) {
            this.showForm  = true
            this.formTitle = 'Ubah Data'
            this.formData  = {
                id:     data.id,
                judul:  data.judul,
                isi:    data.isi,
                status: data.status
            }
        },

        hapus(index, id) {
            if (confirm('Yakin menghapus data?')) {
                axios.delete(apiUrl + '/post/' + id)
                    .then(response => {
                        this.artikel.splice(index, 1)
                    })
                    .catch(error => console.log(error))
            }
        },

        saveData() {
            const judul  = this.formData.judul
            const isi    = this.formData.isi
            const status = this.formData.status

            const params = new URLSearchParams()
            params.append('judul', judul)
            params.append('isi', isi)
            params.append('status', status)

            if (this.formData.id) {
                axios.post(apiUrl + '/post/' + this.formData.id + '/edit', params)
                    .then(response => { this.loadData() })
                    .catch(error => console.log(error))
            } else {
                axios.post(apiUrl + '/post', params)
                    .then(response => { this.loadData() })
                    .catch(error => console.log(error))
            }

            this.formData = { id: null, judul: '', isi: '', status: 0 }
            this.showForm = false
        },

        statusText(status) {
            if (status === null || status === undefined) return ''
            return status == 1 ? 'Publish' : 'Draft'
        }
    },
}).mount('#app')