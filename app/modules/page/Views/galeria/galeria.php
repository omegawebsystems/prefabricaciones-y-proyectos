co<?php
    $galeriaModel = new Administracion_Model_DbTable_Album();
    $albumes = $galeriaModel->getList();
    $fotoModel = new Administracion_Model_DbTable_Foto();
    $galeriaItems = [];
    foreach ($albumes as $key => $album) {
        $fotos = $fotoModel->getByAlbum($album->id);
        array_push($galeriaItems, [
            'id' => $album->id,
            'titulo' => $album->galeria_titulo,
            'descripcion' => $album->galeria_descripcion,
            'imagen_principal' => $album->galeria_imagen,
            'imagenes_galeria' => $fotos,
        ]);
    }
    ?>

<!-- Contenido de la galería -->
<div class="contenedor-seccion" style="background-color: #fff">

    <!-- Grid de la galería -->
    <div class="row">
        <?php foreach ($galeriaItems as $index => $item): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="proyecto-card" style="background-image: url('/images/<?= $item['imagen_principal'] ?>');">

                    <?php foreach ($item['imagenes_galeria'] as $imagen): ?>
                        <a href="<?= '/images/' . $imagen->foto_path ?>"
                            data-fancybox="gallery-<?= $item['id'] ?>"
                            data-caption="<?= $imagen->foto_titulo ?>"
                            style="display: none;"></a>
                    <?php endforeach; ?>

                    <div class="camera-icon">
                        <i class="fas fa-camera"></i>
                    </div>

                    <div class="proyecto-card-content">
                        <h3 class="proyecto-title"><?= $item['titulo'] ?></h3>
                        <div class="proyecto-subtitle"><?= $item['descripcion'] ?></div>

                        <!-- Botón Ver galería -->
                        <?php if (!empty($item['imagenes_galeria'])): ?>
                            <button class="btn-ver-galeria"
                                onclick="abrirGaleria(<?= $item['id'] ?>)"
                                data-fancybox="gallery-<?= $item['id'] ?>"
                                data-src="<?= $item['imagen_principal'] ?>"
                                data-caption="<?= $item['titulo'] ?> - <?= $item['descripcion'] ?>"
                                type="button">
                                Ver galería
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- Estilos CSS adicionales -->
<style>
    .proyecto-card {
        position: relative;
        width: 100%;
        height: 40vh;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 1.5vh;
        overflow: hidden;
        /* cursor: pointer; */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .proyecto-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .proyecto-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom,
                rgba(0, 0, 0, 0.1) 0%,
                rgba(0, 0, 0, 0.3) 50%,
                rgba(227, 174, 36, 0.9) 100%);
        z-index: 1;
    }

    .camera-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        color: white;
        font-size: 3vh;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .proyecto-card:hover .camera-icon {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.1);
    }

    .proyecto-card-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px;
        z-index: 3;
        color: white;
        text-align: center;
    }

    .proyecto-title {
        font-size: 4vh;
        font-weight: 700;
        margin-bottom: 6px;
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        font-family: 'Helvetica', sans-serif;
    }

    .proyecto-subtitle {
        font-size: 2.5vh;
        margin-bottom: 2vh;
        opacity: 0.95;
        line-height: 1.3;
        color: white;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
        font-weight: 400;
    }

    .btn-ver-galeria {
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        border: none;
        padding: 1vh 2vw;
        border-radius: 1vh;
        font-size: 2vh;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        letter-spacing: 0.3px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .btn-ver-galeria:hover {
        background-color: rgba(0, 0, 0, 0.9);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    /* Personalización de Fancybox */
    .fancybox__container {
        --fancybox-bg: rgba(0, 0, 0, 0.9);
    }

    .fancybox__caption {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        color: white;
        padding: 20px;
        font-size: 1.1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .galeria-list-card img {
            height: 250px;
        }

        .titulo-seccion h2 {
            font-size: 2rem;
        }

        .intro-text {
            font-size: 1rem;
        }
    }
</style>

<!-- JavaScript para la funcionalidad de la galería -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Fancybox para todas las galerías
        Fancybox.bind("[data-fancybox]", {
            // Configuración de Fancybox
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: [],
                    right: ["slideshow", "thumbs", "close"]
                }
            },
            Thumbs: {
                autoStart: true
            },
            Images: {
                zoom: true,
                protected: true
            },
            // Personalizar la navegación
            l10n: {
                CLOSE: "Cerrar",
                NEXT: "Siguiente",
                PREV: "Anterior",
                MODAL: "Galería",
                ERROR: "Error al cargar la imagen",
                IMAGE_ERROR: "No se pudo cargar la imagen",
                ELEMENT_NOT_FOUND: "Elemento no encontrado",
                AJAX_NOT_FOUND: "Error: No encontrado",
                AJAX_FORBIDDEN: "Error: Prohibido",
                IFRAME_ERROR: "Error al cargar la página"
            }
        });

        // Inicializar AOS (animaciones)
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
        }
    });

    // Función para abrir galería específica
    function abrirGaleria(galeriaId) {
        // Buscar el primer elemento de la galería específica
        const firstImage = document.querySelector(`[data-fancybox="gallery-${galeriaId}"]`);
        if (firstImage) {
            firstImage.click();
        }
    }

    // Función para añadir efectos adicionales
    function initGalleryEffects() {
        const cards = document.querySelectorAll('.galeria-list-card');

        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    // Ejecutar efectos cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', initGalleryEffects);
</script>