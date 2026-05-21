        // Store cart item (compatible with your existing cart.js)
        let cart = JSON.parse(localStorage.getItem('ryo_cart') || '[]');

        const existingItem = cart.find(item => item.id == productId);

        if (existingItem) {
            existingItem.quantity = (existingItem.quantity || 1) + 1;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                quantity: 1
            });
        }

        localStorage.setItem('ryo_cart', JSON.stringify(cart));

        // Dispatch event for cart drawer update
        const cartEvent = new CustomEvent('ryo:cart-updated', {
            detail: {
                productId,
                quantity: 1,
                productName,
                productPrice
            }
        });
        document.dispatchEvent(cartEvent);

        // Optional: trigger cart drawer count update
        if (typeof updateCartCount === 'function') {
            updateCartCount();
        }

        // Visual feedback on button
        if (button) {
            const originalText = button.innerHTML;
            button.innerHTML =
                '<svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Added!';
            setTimeout(() => {
                button.innerHTML = originalText;
            }, 1000);
        }

        console.log(`Added to cart: ${productName} (ID: ${productId})`);
        



        // Wishlist toggle function
        function toggleWishlist(productId) {
            let wishlist = JSON.parse(localStorage.getItem('ryo_wishlist') || '[]');

            const index = wishlist.indexOf(productId);
            if (index > -1) {
                wishlist.splice(index, 1);
                showToastMessage('Removed from wishlist');
            } else {
                wishlist.push(productId);
                showToastMessage('Added to wishlist');
            }

            localStorage.setItem('ryo_wishlist', JSON.stringify(wishlist));
        }

        function showToastMessage(message) {
            const toast = document.getElementById('quickAddToast');
            const toastMsg = document.getElementById('quickAddToastMsg');
            if (toast && toastMsg) {
                toastMsg.innerText = message;
                toast.style.transform = 'translateX(-50%) translateY(0px)';
                toast.style.opacity = '1';
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(-50%) translateY(100px)';
                }, 2000);
            }
        }

        // Helper to toggle cart drawer (delegates to global `toggleCart` when available)
        function toggleCart() {
            if (typeof window.toggleCart === 'function') {
                return window.toggleCart();
            }
            const drawer = document.querySelector('.cart-drawer, [data-cart-drawer]');
            if (drawer) {
                drawer.classList.toggle('active');
            } else {
                // Fallback: dispatch event
                document.dispatchEvent(new CustomEvent('ryo:open-cart'));
            }
        }

        // Update cart count from localStorage
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('ryo_cart') || '[]');
            const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
            const countElements = document.querySelectorAll('.cart-count, [data-cart-count]');
            countElements.forEach(el => {
                el.textContent = totalItems;
                el.style.display = totalItems > 0 ? 'flex' : 'none';
            });
        }

        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', updateCartCount);

        // Listen for cart updates
        document.addEventListener('ryo:cart-updated', updateCartCount);
