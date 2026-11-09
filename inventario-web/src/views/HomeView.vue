<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const url = 'http://localhost:8000/api/productos'
const productos = ref([])
const todosLosProductos = ref([])
const cargando = ref(false)


async function cargarProductos() {
    cargando.value = true
    try {
        const respuesta = await axios.get(url)
        productos.value = respuesta.data
        todosLosProductos.value = respuesta.data

        if (productos.value.length === 0) {
            alert('No hay productos disponibles.')
        }
    } catch (error) {
        alert('No se pudieron cargar los productos por un error del servidor. (¿Estará caído?)')
        console.error(error)
    } finally {
        cargando.value = false
    }
}

const nombre = ref('')
const descripcion = ref('')
const precio = ref(0)
const stock = ref(0)
const guardando = ref(false)
const productoEditando = ref(null)
const filtro = ref('')

async function crearProducto() {
    if (guardando.value) return
    guardando.value = true
    try {
        await axios.post(url, {
            nombre: nombre.value,
            descripcion: descripcion.value,
            precio: precio.value,
            stock: stock.value
        },
            {
                headers: {
                    Accept: 'application/json'
                }
            })
        nombre.value = ''
        descripcion.value = ''
        precio.value = 0
        stock.value = 0
        await cargarProductos()
    } catch (error) {
        const errores = error.response?.data?.errors
        alert(errores ? Object.values(errores).flat().join('\n') : 'No se pudo crear el producto.')
        console.error(error)
    } finally {
        guardando.value = false
    }
}

async function eliminarProducto(producto) {
    if (!confirm(`¿Está seguro de que desea eliminar el producto "${producto.nombre}"?`)) return
    cargando.value = true
    try {
        await axios.delete(`${url}/${producto.id}`)
        await cargarProductos()
    } catch (error) {
        alert('No se pudo eliminar el producto.')
        console.error(error)
    } finally {
        cargando.value = false
    }
}

async function modoEdicion(producto) {
    nombre.value = producto.nombre
    descripcion.value = producto.descripcion
    precio.value = producto.precio
    stock.value = producto.stock
    productoEditando.value = producto
}

async function editarProducto() {
    if (guardando.value) return
    guardando.value = true
    try {
        await axios.put(`${url}/${productoEditando.value.id}`, {
            nombre: nombre.value,
            descripcion: descripcion.value,
            precio: precio.value,
            stock: stock.value
        })
        nombre.value = ''
        descripcion.value = ''
        precio.value = 0
        stock.value = 0
        productoEditando.value = null
        await cargarProductos()
    } catch (error) {
        alert('No se pudo editar el producto.')
        console.error(error)
    } finally {
        guardando.value = false
    }
}

async function cancelarEdicion() {
    productoEditando.value = null
    nombre.value = ''
    descripcion.value = ''
    precio.value = 0
    stock.value = 0
}

async function filtrarProductos() {
    try {
        const termino = filtro.value.trim().toLowerCase()
        productos.value = todosLosProductos.value.filter(producto =>
            producto.nombre.toLowerCase().includes(termino)
        )
    } catch (error) {
        alert('No se pudieron filtrar los productos.')
        console.error(error)
    }
}

onMounted(cargarProductos)

// Debo decir, esto me costo bastante y en parte me complique la vida un poco. Porque no quise usar IA y me tome casi la mitad del dia, aunque si use codigo reutilizable de
// otras practicas como de Lue con el caso de la API, o mi propio codigo de otros proyectos personales de laravel y vue 
// pd: con complicarme la vida incluyo publicar en github para descargar el zip sin el vendor y node_modules, o publicar los archivos de lenguaje para errores de validacion
// o incluso configurar manualmente cors en laravel (config/cors.php) para que funcione con Vue porque segun el archivo de la evaluacion me parecio que era 
// explicita la indicacion "configura laravel para permitir peticiones desde el origen de vue", 
// pero lo cierto es que sin cors claro que funciona, solo que tambien podria entrar a la api desde otro sitio web. Esa parte fue confusa
// o que tambien inclui sanctum al principio solo para darme cuenta con la api ya terminada que en el archivo de la evaluacion ni lo menciona
// asi que ahi se quedo, pero solo quite las rutas y el middleware y ya con eso es como si no estara el sanctum instalado
// o para empezar, para que le puse vue router y vuetify? pero al final del dia, esto me gusta, quebrarme el coco me divierte
// la parte de la edicion con el mismo formulario me dejo en blanco, como sabia que el mismo boton debia crear o editar, y cancelar??
// hasta que se me ocurrio el boton del v-if y usar una flag, desde ahi fluyo todo
// bueno eso si, el CSS si lo pedi a IA como no era parte de la rubrica que se viera bonito
// al menos asi no parece html puro
</script>

<template>
    <h3>Gestión de Productos</h3>
    <form @submit.prevent="productoEditando ? editarProducto() : crearProducto()">
        <p>
            <label>Nombre
                <input v-model="nombre" />
            </label>
        </p>
        <p>
            <label>Descripcion
                <input v-model="descripcion" />
            </label>
        </p>
        <p>
            <label>Precio
                <input v-model="precio" type="number" max="999999.99" step="0.01" required />
                <!-- Podria poner min:0 pero entonces no se veria la validacion desde laravel, 
                 igual que maxlength y required, si lo pongo aqui no sale el alert desde laravel, el navegador lo intercepta antes
                 obviamente es mucho mejor interceptar desde el formulario para evitar error 500 por si las dudas -->
            </label>
        </p>
        <p>
            <label>Stock
                <input v-model.number="stock" type="number" max="1000000" step="1" required />
            </label>
        </p>
        <button type="submit" :disabled="guardando" id="guardar-producto">Guardar producto</button>
        <button type="button" @click="cancelarEdicion()" :disabled="guardando" id="cancelar-edicion"
            v-if="productoEditando">Cancelar edición</button>
    </form>
    <form @submit.prevent="filtrarProductos()">
        <input v-model="filtro" placeholder="Filtrar productos" @input="filtrarProductos()" />
    </form>
    <section>
        <p id="cargando" v-if="cargando">Cargando productos...</p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="producto in productos" :key="producto.id">
                    <td>{{ producto.id }}</td>
                    <td>{{ producto.nombre }}</td>
                    <td>{{ producto.descripcion }}</td>
                    <td>{{ producto.precio }}</td>
                    <td>{{ producto.stock }}</td>
                    <td>
                        <button @click="modoEdicion(producto)">Editar</button>
                        <button @click="eliminarProducto(producto)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</template>

<script>
export default {
    name: 'HomeView'
}
</script>

<style scoped>
 h1 {
    margin: 0 0 1.5rem;
    color: #172554;
    font-size: clamp(1.75rem, 4vw, 2.4rem);
    letter-spacing: -0.04em;
}

form:first-of-type {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    margin-bottom: 1.25rem;
    padding: 1.25rem;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 24px rgb(15 23 42 / 6%);
}

form:first-of-type p {
    margin: 0;
}

label {
    display: grid;
    gap: 0.45rem;
    color: #334155;
    font-size: 0.9rem;
    font-weight: 600;
}

input {
    box-sizing: border-box;
    width: 100%;
    min-height: 42px;
    padding: 0.65rem 0.8rem;
    border: 1px solid #cbd5e1;
    border-radius: 9px;
    background: #f8fafc;
    color: #0f172a;
    font: inherit;
    transition: border-color 150ms ease, box-shadow 150ms ease, background 150ms ease;
}

input:focus {
    outline: none;
    border-color: #3b82f6;
    background: #fff;
    box-shadow: 0 0 0 3px rgb(59 130 246 / 16%);
}

button {
    min-height: 38px;
    margin-right: 0.4rem;
    padding: 0.55rem 0.9rem;
    border: 0;
    border-radius: 8px;
    background: #2563eb;
    color: #fff;
    font: inherit;
    font-weight: 600;
    cursor: pointer;
    transition: background 150ms ease, transform 150ms ease;
}

button:hover:not(:disabled) {
    transform: translateY(-1px);
    background: #1d4ed8;
}

button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

#cancelar-edicion {
    background: #64748b;
}

#cancelar-edicion:hover:not(:disabled) {
    background: #475569;
}

form:first-of-type button {
    align-self: end;
}

form:nth-of-type(2) {
    max-width: 420px;
    margin: 0 0 1rem auto;
}

form:nth-of-type(2) input {
    background: #fff;
}

section {
    overflow-x: auto;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 8px 24px rgb(15 23 42 / 5%);
}

table {
    width: 100%;
    border-collapse: collapse;
    color: #334155;
    font-size: 0.95rem;
}

th, td {
    border-bottom: 1px solid #e2e8f0;
    padding: 0.85rem 1rem;
    text-align: left;
}

th {
    background: #f1f5f9;
    color: #1e293b;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

tbody tr:last-child td {
    border-bottom: 0;
}

tbody tr:hover {
    background: #f8fafc;
}

td button:last-child {
    background: #dc2626;
}

td button:last-child:hover:not(:disabled) {
    background: #b91c1c;
}

#cargando {
    margin: 0;
    padding: 0.8rem 1rem;
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 600;
}

@media (max-width: 600px) {
    form:first-of-type {
        grid-template-columns: 1fr;
        padding: 1rem;
    }

    form:first-of-type button {
        width: 100%;
    }

    th, td {
        padding: 0.7rem;
    }
}
</style>