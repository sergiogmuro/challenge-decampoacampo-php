const API_BASE = `${window.env.API_URL}`;

const request = async (path, method = 'GET', data = null) => {
    const body = data ? JSON.stringify(data) : undefined;

    return await fetch(`${API_BASE}${path}`, {
        method: method.toUpperCase(),
        headers: {
            'Content-Type': 'application/json'
        },
        body
    });

}

const loadProducts = async () => {
    try {
        const noProductsEl = document.querySelector('.no-products');
        const response = await request(`/productos`);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const {data} = await response.json();

        openPopup(noProductsEl)
        if (data.length > 0) {
            closePopup(noProductsEl)
        }

        return data;
    } catch (error) {
        console.error(error.message);
    }
}

const getProduct = async (id) => {
    try {
        const response = await request(`/productos/${id}`);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const {data} = await response.json();
        return data;
    } catch (error) {
        console.error(error.message);
    }
}

const newProduct = async (data) => {
    try {
        return await request(`/productos/`, 'POST', data);
    } catch (error) {
        console.error("Error al actualizar:", error);
    }
}

const updateProduct = async (id, data) => {
    try {
        return await request(`/productos/${id}`, 'PUT', data);
    } catch (error) {
        console.error("Error al actualizar:", error);
    }
}
const deleteProduct = async (id) => {
    try {
        return await request(`/productos/${id}`, 'DELETE');
    } catch (error) {
        console.error("Error al actualizar:", error);
    }
}

const openPopup = (popupEl) => {
    popupEl.classList.remove('hidden')
}

const closePopup = (popupEl) => {
    popupEl.classList.add('hidden')
}

const showProducts = (products) => {
    const popup = document.getElementById('editPopup');
    const confirmationPopup = document.getElementById('confirmationDeletePopup');

    document.getElementById('cancelEdit').addEventListener('click', () => closePopup(popup));
    document.getElementById('cancelDelete').addEventListener('click', () => closePopup(confirmationPopup));

    if ("content" in document.createElement("template")) {
        const tbody = document.querySelector("#tbody");
        const template = document.querySelector("#productrow");

        products.forEach((p) => {
            let productRow
            if (document.querySelector(`#product[data-id="${p.id}"]`)) {
                productRow = document.querySelector(`#product[data-id="${p.id}"]`);
            } else {
                const cloneTemplate = document.importNode(template.content, true);

                productRow = cloneTemplate.querySelector(".product");
                productRow.setAttribute('data-id', p.id);

                tbody.appendChild(cloneTemplate);
            }

            let cols = productRow.querySelectorAll("div");
            cols[0].textContent = `#${p.id}`;
            cols[1].textContent = p.nombre;
            cols[2].textContent = p.descripcion;
            cols[3].textContent = `ARS ${p.precio}`;
            cols[4].textContent = `USD ${p.precio_usd}`;

            cols[5].querySelector('#edit').addEventListener('click', async (e) => {
                const productRow = e.target.closest('.product');
                const productId = productRow.getAttribute('data-id');

                const productInfo = await getProduct(productId);

                popup.querySelector('#editProductId').value = productInfo.id;
                popup.querySelector('#editName').value = productInfo.nombre;
                popup.querySelector('#editDescription').value = productInfo.descripcion;
                popup.querySelector('#editPrice').value = productInfo.precio;

                openPopup(popup)
            });

            cols[5].querySelector('#delete').addEventListener('click', async (e) => {
                const productRow = e.target.closest('.product');

                confirmationPopup.querySelector('#deleteProductId').value = productRow.getAttribute('data-id');

                openPopup(confirmationPopup)
            });
        })
    }
}

const openNotification = (title, message, className = 'success') => {
    const notificationPopup = document.getElementById('notificationPopup');
    notificationPopup.querySelector('#notificationPopupContent').classList.add(className);
    notificationPopup.querySelector('#notificationTitle').innerHTML = title
    notificationPopup.querySelector('#notificationMessage').innerHTML = message

    notificationPopup.addEventListener('click', (event) => {
        notificationPopup.classList.remove(className);
        notificationPopup.querySelector('#notificationPopupContent').classList.remove(className);
        closePopup(notificationPopup)
    })

    openPopup(notificationPopup)
}

const setEvents = () => {
    const popup = document.getElementById('editPopup');
    const confirmationPopup = document.getElementById('confirmationDeletePopup');

    document.getElementById('new').addEventListener('click', async (event) => {
        popup.querySelector('#editProductId').value = null;
        popup.querySelector('#editName').value = null;
        popup.querySelector('#editDescription').value = null;
        popup.querySelector('#editPrice').value = null;

        openPopup(popup)
    })

    document.getElementById('editForm').addEventListener('submit', async (event) => {
        event.preventDefault();

        const id = document.getElementById('editProductId').value;
        const data = {
            nombre: document.getElementById('editName').value,
            descripcion: document.getElementById('editDescription').value,
            precio: parseFloat(document.getElementById('editPrice').value)
        };

        let response;
        if (id) {
            response = await updateProduct(id, data)
        } else {
            response = await newProduct(data)
        }

        if (response.ok) {
            closePopup(popup);
            const products = await loadProducts();
            showProducts(products);

            if (id) {
                openNotification('Actualizado', 'El producto fue actualizado correctamente')
            } else {
                openNotification('Creado', 'El producto fue creado con exito!')
            }
        } else {
            openNotification('Error', 'Ocurrio un error', 'error')
        }

    });

    document.getElementById('confirmDelete').addEventListener('click', async (e) => {
        const id = document.getElementById('deleteProductId').value;

        try {

            const response = await deleteProduct(id)
            if (response.ok) {
                document.querySelector(`#product[data-id="${id}"]`).remove();
                closePopup(confirmationPopup);
                const products = await loadProducts();
                showProducts(products);
                openNotification('Eliminado', 'El producto fue eliminado correctamente!')
            } else {
                openNotification('Error', 'Ocurrio un error al eliminar el producto', 'error')
            }
        } catch (error) {
            console.error("Error al eliminar:", error);
        }
    });
}

const start = async () => {
    setEvents();

    const products = await loadProducts();

    showProducts(products)
}

document.onreadystatechange = () => {
    if (document.readyState === "complete") {
        start();
    }
}
