@push('styles')
    <style>
        .submission-title{
            font-weight: bold;
            color: var(--primary-white);
            /* 'position: center' bukan CSS valid, dihapus agar tidak error */
        }
        
        /* Tambahan agar gambar responsif dan tidak melebar keluar layar di HP */
        .submission-photo-animation img {
            max-width: 100%;
            height: auto;
        }
    </style>
@endpush

{{-- Container utama --}}
<div class="relative w-screen left-1/2 -translate-x-1/2 h-auto overflow-hidden">
    <div class="absolute inset-0 w-full h-full z-0" 
         style="background-color: #000;
                background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.8)), 
                                  url('https://as1.ftcdn.net/jpg/01/00/30/58/1000_F_100305801_Iuo4E3KhUYyjxidZWCsXBbwKDpBeHY7Q.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;">
    </div>

    {{-- UBAHAN 1: Padding atas-bawah dikurangi saat mode mobile (py-10) --}}
    <div class="relative z-10 container mx-auto py-10 md:py-20 px-4"> 
        
        {{-- UBAHAN 2: Flex direction column di HP, Row di Desktop (md). Text align center di HP. --}}
        <div class="flex flex-col md:flex-row items-center justify-center gap-6 text-center md:text-left">
            
            {{-- UBAHAN 3: Font size dibuat responsif (lebih kecil di HP) --}}
            <div class="submission-title font-montech-bold leading-tight animate-from-left text-4xl md:text-[50px]">
                <h1> 
                    SUBMISSIONS <br>
                    ARE NOW OPEN!
                </h1>
            </div>

            <div class="submission-title font-inter-regular animate-from-right text-base md:text-[20px]"> 
                Select one of the <br>
                categories to learn more <br>
                or submit your films!
            </div>
        </div>

        {{-- UBAHAN 4: Gambar ditumpuk (column) di HP, berjajar (row) di Desktop --}}
        <div class="flex flex-col md:flex-row items-center justify-center mt-10 md:mt-7 gap-6 md:gap-10">
            
            <div class="submission-photo-animation w-full md:w-auto flex justify-center" id="submission-photo-1">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARwAAACxCAMAAAAh3/JWAAABpFBMVEUQEBAAAACH8MGM3eKG9LqG8r2L4duF97WJ6c2N2en9wVn9xVb9xlT9yFP9y1GG8b/7ioX7jYP7j4CM3OON2+b9z077g4v7hoj7iIb90UyF9bn7fo77gYz7k375fY+O1+z+1Un7l3r8m3f8n3T5epIAAAyK49f8smWK5NaO1fD5dpWI68n9tmH8o3H+2Ub8sWaJ6M78qG39vF3/dJ0ACQA4UFuEPVOE+bJ8XjAvTkEpHBr/5kMpGxtNgm91vLmO8tdvuKhyxaltwJt55KJ6x758wMrYmlZ8TT99UjvmiHNlSyotRkJBaGJPgXpblI1lo5xwsbNknaBbjZFOeHw9XWEnNzocJSiC3MJzrb8pOj9GdWV5zLeAvNcfLylFY3FpsZxRinVFNR6IazFNQB49alNZn31pvJO7mjooQzQqJRQwUz7Rszt4aCVern+LeSlowowYIhtWSxxKiGGpgD9LbX17w8WLYTy9hE5JKS2iU13TbHdpXR9Bd1bXZIPlyjxXKzfAqTOpjjNeQSzWlVjajmNxNkc1KBxxVC7aY4abbUJQLDCIUkWC6eMGAAAF5UlEQVR4nO3d+VsTRxzH8XwtGI56gwr1AHWhQMAEUwGhbpSzHILSAiZNuY9qLVotqNXaIkex/3RnZjfHE3azC8+u6WQ+7x99fHiS1zM7M3tBIIAQQgghhBBCCCGEEEIIIYQQQgghhBBCCKHDRAcr9Ef6XyQkRkbHHoyP3+VNTEw8fDT5/Q8AYgBdD2Jf8xoaGo7zvhCdq6rqfzg5pbAP0ehsWA+bNsePZ3DOMZyq8vLy/keK+hDFp/Uwyw6H61y9OvNYQR0amdbLws44jEe5wUNxvazMHU5Tk2KDh+ItLU445SYO0/lGJR1KtmRwws44TVOF/sSfMbpn4OiJsdGxhAucHxUaOnzgMBx9TmwBx5xxmn5SRofPOBxn1vjGNO6Mo87QoTmBoyfNb/zcGadJHZxvjZGT+sLkAkeZBcvE0YFjUQqny5xzRoCTKXVYJUycPuBkSuHoCXE5J+ZiKVcPpyysx8ZjLk4f1MRxeW4FHOCIgJMn4OTJEif7ArvSOPF7RolETNTHEjdm7gLH6iaeGXDsy4+jZSr0B/U0+7HifuTML6RbLB4fjWhpeWVl5b6ROeHMzsayMqaciTw4WmdlZeWXJ06cPFldXVO9Ol8cOrS2UltSUlp66tQxoxajnNXKaSnXOisqKjI8batFMHiIVmqFTRonbXO4fU4OTk1dzc+y69BabZDZ+IFTVye5Dj0JBmv9wmmrkxpHSwZ9xKlreyqzDvX6i9P9i7w69KzDX5y2NmlxtCS38RWn+1dZdWjdd5y2P2XFed7hP073Czl1aNlDnMd2OKuS4vR6iDMVsMaRdEqmZIeHOBTQFixxuqXcJtNvHuLMMJyX1jhSrldsrfIO5xUFaMAa5y8pcXo9xCH289qtcaRczKnDOxw2cLTFCmscGWdkMR97hMMfehNTjtVq1S0jzpJnOOKBQK3d+rCScrmidx7hGDb00hZHwj2yWMmNk3JnnIYcnKosnFdi3My3p3FO5uD8ISHOs0Pg2F5g758Ur4VogZA9joTXdJxxdF0PJ3h96X436hfNpF9H0+5wGzscCXeBjjjT412O9/jET9JoL6QWTlnc1UucmkY0PBBSC2fWkIny4dFjdMei3a2NzZuhkFo4cybN6zdvL50/f+HCxdNnzpw9e/lyfX39tWvXrl+/3traGmHd5DU2Fh/Osj2OeCMkSrevXPnqkolzmuFk6bRm6TjhyLha2e9zpsWu7vWNG97gyLjPeWeHc4w/1k87zV7hSLhD1p7Y4dxjNtGdZs9wCv1Nj1LSDidOgWhPs3c48g2czPWcAzj8oHrvGY6kF7t6rXHus4Hz4ZZ3OHJeJl23xlkmNnA8xJFwm5O5+5CLw6YcuuUhzqKMONqaNQ5byHu4jVcTsow2bOgEbXCiO97hyDnlGGdX1ji3PcSRcAvI49tA33GkXMh5bDH3G0fGs04jtl75jSPtwEk/L+kfjqwzDo+WfMb5W14bvmD5iSP3Y8jiwPINR3YbU8cXHLmPKSNaD/qAU1P3VMpzqtzoXQkbO65xDtx9SNkImkrxGEFN9eoLKgYb/sbV0kppSfp1tHw43w2KhjLt8TqzWlhY6JynIngRLVXOr6jNgyNuAmuOFeyb+F8+nEJ/toIHnDw5T8jmfBxJzceh9uFiPpSyO8JSDhzgBICTN+DkCTh5cocT2Rza2vgIHEucLb5Tpn3gWOAMmb9+8iNwDuKYe2VtuBE4uTjbqROJOwbOnjI4Lm4Hp3F2FcNx9SCB+V+1DdVw6B/nR1CMoaNp5mo1r8z5unFcOSzlmxoR7Q6Y5+XK2Lh7JjAS2d78mNoh7ytzVLGihzt9GFBo4ATEs7aucUKNhf64n7lo9L1bnNCASseUEe0wHTc4G0odU2b8tRlnnP2ivhGTpyh9esNxLlqvVpHI5laR3Ng8UlGinp03b1Mvo2VuB29v7w/tkso0Rsarrq8/DQ7+yxsc3NoV/6Y8TKZo6k6w8n/dFCGEEEIIIYQQQgghhBBCCCGEEEIIIYQQOlL/AfDmgCCmYJ6HAAAAAElFTkSuQmCC" alt="GAP IN A MINUTE">
            </div>
            
            <div class="submission-photo-animation w-full md:w-auto flex justify-center" id="submission-photo-2">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARwAAACxCAMAAAAh3/JWAAABpFBMVEUQEBAAAACH8MGM3eKG9LqG8r2L4duF97WJ6c2N2en9wVn9xVb9xlT9yFP9y1GG8b/7ioX7jYP7j4CM3OON2+b9z077g4v7hoj7iIb90UyF9bn7fo77gYz7k375fY+O1+z+1Un7l3r8m3f8n3T5epIAAAyK49f8smWK5NaO1fD5dpWI68n9tmH8o3H+2Ub8sWaJ6M78qG39vF3/dJ0ACQA4UFuEPVOE+bJ8XjAvTkEpHBr/5kMpGxtNgm91vLmO8tdvuKhyxaltwJt55KJ6x758wMrYmlZ8TT99UjvmiHNlSyotRkJBaGJPgXpblI1lo5xwsbNknaBbjZFOeHw9XWEnNzocJSiC3MJzrb8pOj9GdWV5zLeAvNcfLylFY3FpsZxRinVFNR6IazFNQB49alNZn31pvJO7mjooQzQqJRQwUz7Rszt4aCVern+LeSlowowYIhtWSxxKiGGpgD9LbX17w8WLYTy9hE5JKS2iU13TbHdpXR9Bd1bXZIPlyjxXKzfAqTOpjjNeQSzWlVjajmNxNkc1KBxxVC7aY4abbUJQLDCIUkWC6eMGAAAF5UlEQVR4nO3d+VsTRxzH8XwtGI56gwr1AHWhQMAEUwGhbpSzHILSAiZNuY9qLVotqNXaIkex/3RnZjfHE3azC8+u6WQ+7x99fHiS1zM7M3tBIIAQQgghhBBCCCGEEEIIIYQQQgghhBBCCKHDRAcr9Ef6XyQkRkbHHoyP3+VNTEw8fDT5/Q8AYgBdD2Jf8xoaGo7zvhCdq6rqfzg5pbAP0ehsWA+bNsePZ3DOMZyq8vLy/keK+hDFp/Uwyw6H61y9OvNYQR0amdbLws44jEe5wUNxvazMHU5Tk2KDh+ItLU445SYO0/lGJR1KtmRwws44TVOF/sSfMbpn4OiJsdGxhAucHxUaOnzgMBx9TmwBx5xxmn5SRofPOBxn1vjGNO6Mo87QoTmBoyfNb/zcGadJHZxvjZGT+sLkAkeZBcvE0YFjUQqny5xzRoCTKXVYJUycPuBkSuHoCXE5J+ZiKVcPpyysx8ZjLk4f1MRxeW4FHOCIgJMn4OTJEif7ArvSOPF7RolETNTHEjdm7gLH6iaeGXDsy4+jZSr0B/U0+7HifuTML6RbLB4fjWhpeWVl5b6ROeHMzsayMqaciTw4WmdlZeWXJ06cPFldXVO9Ol8cOrS2UltSUlp66tQxoxajnNXKaSnXOisqKjI8batFMHiIVmqFTRonbXO4fU4OTk1dzc+y69BabZDZ+IFTVye5Dj0JBmv9wmmrkxpHSwZ9xKlreyqzDvX6i9P9i7w69KzDX5y2NmlxtCS38RWn+1dZdWjdd5y2P2XFed7hP073Czl1aNlDnMd2OKuS4vR6iDMVsMaRdEqmZIeHOBTQFixxuqXcJtNvHuLMMJyX1jhSrldsrfIO5xUFaMAa5y8pcXo9xCH289qtcaRczKnDOxw2cLTFCmscGWdkMR97hMMfehNTjtVq1S0jzpJnOOKBQK3d+rCScrmidx7hGDb00hZHwj2yWMmNk3JnnIYcnKosnFdi3My3p3FO5uD8ISHOs0Pg2F5g758Ur4VogZA9joTXdJxxdF0PJ3h96X436hfNpF9H0+5wGzscCXeBjjjT412O9/jET9JoL6QWTlnc1UucmkY0PBBSC2fWkIny4dFjdMei3a2NzZuhkFo4cybN6zdvL50/f+HCxdNnzpw9e/lyfX39tWvXrl+/3traGmHd5DU2Fh/Osj2OeCMkSrevXPnqkolzmuFk6bRm6TjhyLha2e9zpsWu7vWNG97gyLjPeWeHc4w/1k87zV7hSLhD1p7Y4dxjNtGdZs9wCv1Nj1LSDidOgWhPs3c48g2czPWcAzj8oHrvGY6kF7t6rXHus4Hz4ZZ3OHJeJl23xlkmNnA8xJFwm5O5+5CLw6YcuuUhzqKMONqaNQ5byHu4jVcTsow2bOgEbXCiO97hyDnlGGdX1ji3PcSRcAvI49tA33GkXMh5bDH3G0fGs04jtl75jSPtwEk/L+kfjqwzDo+WfMb5W14bvmD5iSP3Y8jiwPINR3YbU8cXHLmPKSNaD/qAU1P3VMpzqtzoXQkbO65xDtx9SNkImkrxGEFN9eoLKgYb/sbV0kppSfp1tHw43w2KhjLt8TqzWlhY6JynIngRLVXOr6jNgyNuAmuOFeyb+F8+nEJ/toIHnDw5T8jmfBxJzceh9uFiPpSyO8JSDhzgBICTN+DkCTh5cocT2Rza2vgIHEucLb5Tpn3gWOAMmb9+8iNwDuKYe2VtuBE4uTjbqROJOwbOnjI4Lm4Hp3F2FcNx9SCB+V+1DdVw6B/nR1CMoaNp5mo1r8z5unFcOSzlmxoR7Q6Y5+XK2Lh7JjAS2d78mNoh7ytzVLGihzt9GFBo4ATEs7aucUKNhf64n7lo9L1bnNCASseUEe0wHTc4G0odU2b8tRlnnP2ivhGTpyh9esNxLlqvVpHI5laR3Ng8UlGinp03b1Mvo2VuB29v7w/tkso0Rsarrq8/DQ7+yxsc3NoV/6Y8TKZo6k6w8n/dFCGEEEIIIYQQQgghhBBCCCGEEEIIIYQQOlL/AfDmgCCmYJ6HAAAAAElFTkSuQmCC" alt="STUDENT GAP STANDERS">
            </div>
            
            <div class="submission-photo-animation w-full md:w-auto flex justify-center" id="submission-photo-3">
                <img src="data:image/png;base64,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" alt="VOICES IN THE GAP">
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
    gsap.registerPlugin(ScrollTrigger);

    gsap.from(".animate-from-left",{
        scrollTrigger: {
            trigger: ".animate-from-left",
            start: "top 80%",
            toggleActions: "play pause resume reverse"
        },
        x: -150,
        opacity: 0,
        duration: 1.5,
        ease: "power2.out"
    });

    gsap.from(".animate-from-right",{
        scrollTrigger: {
            trigger: ".animate-from-right",
            start: "top 80%",
            toggleActions: "play pause resume reverse"
        },
        x: 150,
        opacity: 0,
        duration: 1.5,
        ease: "power2.out"
    });

    gsap.from(".submission-photo-animation",{
        scrollTrigger: {
            trigger: ".submission-photo-animation",
            start: "top 80%",
            toggleActions: "play pause resume reverse"
        },
        opacity: 0,
        y: 100,
        duration: 1.7,
        ease: "power2.out",
        stagger: 0.3
    });
</script>
@endpush