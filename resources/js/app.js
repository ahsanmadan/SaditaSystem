import Alpine from 'alpinejs';
import anchor from '@alpinejs/anchor';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import { registerCharts } from './blatui-charts';

window.Alpine = Alpine;
Alpine.plugin(anchor);
Alpine.plugin(collapse);
Alpine.plugin(focus);
registerCharts(Alpine);
Alpine.start();

const onReady = (callback) => {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback, { once: true });
        return;
    }

    callback();
};

const createElement = (tag, className, textContent) => {
    const element = document.createElement(tag);

    if (className) {
        element.className = className;
    }

    if (textContent !== undefined) {
        element.textContent = textContent;
    }

    return element;
};

const setButtonIcon = (container, type) => {
    if (!container) {
        return;
    }

    if (type === 'whatsapp') {
        container.innerHTML =
            '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>';
        return;
    }

    container.innerHTML =
        '<svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>';
};

const initPreloader = () => {
    const preloader = document.getElementById('preloader');

    if (!preloader) {
        return;
    }

    const hidePreloader = () => {
        if (preloader.dataset.hidden === 'true') {
            return;
        }

        preloader.dataset.hidden = 'true';
        preloader.style.opacity = '0';

        window.setTimeout(() => {
            preloader.style.display = 'none';
        }, 500);
    };

    window.addEventListener('load', hidePreloader, { once: true });
    window.setTimeout(hidePreloader, 800);
};

const initNavbar = () => {
    const navbar = document.getElementById('main-navbar');
    const hamburgerButton = document.getElementById('hamburger-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const sectionLinks = document.querySelectorAll('[data-section-link]');
    const catalogMenuTrigger = document.querySelector('[data-nav-dropdown-trigger]');
    const catalogMenuPanel = document.querySelector('[data-nav-dropdown]');
    const mobileCatalogTrigger = document.querySelector('[data-mobile-catalog-trigger]');
    const mobileCatalogPanel = document.querySelector('[data-mobile-catalog-panel]');

    if (!navbar || !hamburgerButton || !mobileMenu) {
        return;
    }

    const navbarAlwaysSolid = navbar.classList.contains('navbar-solid');
    let menuOpen = false;
    let catalogMenuOpen = false;

    const setActiveSectionLink = (sectionId) => {
        if (!sectionLinks.length) {
            return;
        }

        sectionLinks.forEach((link) => {
            if (!(link instanceof HTMLElement)) {
                return;
            }

            if (link.dataset.sectionLink === sectionId) {
                link.setAttribute('aria-current', 'page');
                return;
            }

            link.removeAttribute('aria-current');
        });
    };

    const updateNavbar = () => {
        navbar.classList.toggle(
            'navbar-scrolled',
            navbarAlwaysSolid || window.scrollY > 50 || menuOpen || catalogMenuOpen
        );
    };

    const sectionAliases = [
        { id: 'beranda', nav: 'beranda' },
        { id: 'kategori', nav: 'kategori' },
        { id: 'kategori-papan-ucapan', nav: 'kategori' },
        { id: 'kategori-papan-bunga', nav: 'kategori' },
        { id: 'kategori-hantaran', nav: 'kategori' },
        { id: 'kategori-dekorasi', nav: 'kategori' },
        { id: 'kategori-alasan', nav: 'kategori' },
        { id: 'galeri', nav: 'galeri' },
        { id: 'cara-pesan', nav: 'cara-pesan' },
        { id: 'lacak', nav: 'lacak' },
        { id: 'tentang', nav: 'tentang' },
    ];

    const pageSections = sectionAliases
        .map((item) => {
            const element = document.getElementById(item.id);

            if (!element) {
                return null;
            }

            return {
                element,
                nav: item.nav,
            };
        })
        .filter(Boolean);

    const updateActiveSection = () => {
        if (!pageSections.length) {
            return;
        }

        const activationLine = window.innerHeight * 0.3;
        let activeSectionId = pageSections[0].nav;

        pageSections.forEach((section) => {
            const rect = section.element.getBoundingClientRect();

            if (rect.top <= activationLine && rect.bottom >= activationLine) {
                activeSectionId = section.nav;
            }
        });

        setActiveSectionLink(activeSectionId);
    };

    const openMenu = () => {
        menuOpen = true;
        mobileMenu.classList.remove('translate-x-full');
        mobileMenu.classList.add('translate-x-0');
        hamburgerButton.classList.add('is-active');
        document.body.style.overflow = 'hidden';
        updateNavbar();
    };

    const closeMenu = () => {
        menuOpen = false;
        mobileMenu.classList.add('translate-x-full');
        mobileMenu.classList.remove('translate-x-0');
        hamburgerButton.classList.remove('is-active');
        document.body.style.overflow = '';
        updateNavbar();
    };

    const setCatalogMenuState = (nextState) => {
        if (!catalogMenuTrigger || !catalogMenuPanel) {
            return;
        }

        catalogMenuOpen = nextState;
        catalogMenuTrigger.setAttribute('aria-expanded', String(nextState));
        catalogMenuPanel.setAttribute('aria-hidden', String(!nextState));
        catalogMenuPanel.classList.toggle('pointer-events-none', !nextState);
        catalogMenuPanel.classList.toggle('opacity-0', !nextState);
        catalogMenuPanel.classList.toggle('-translate-y-3', !nextState);
        catalogMenuPanel.classList.toggle('is-open', nextState);
        updateNavbar();
    };

    const closeCatalogMenu = () => setCatalogMenuState(false);

    const toggleMobileCatalog = (forceOpen) => {
        if (!mobileCatalogTrigger || !mobileCatalogPanel) {
            return;
        }

        const nextState =
            typeof forceOpen === 'boolean'
                ? forceOpen
                : mobileCatalogTrigger.getAttribute('aria-expanded') !== 'true';

        mobileCatalogTrigger.setAttribute('aria-expanded', String(nextState));
        mobileCatalogPanel.classList.toggle('is-open', nextState);
        mobileCatalogPanel.hidden = !nextState;
    };

    hamburgerButton.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();

        if (menuOpen) {
            closeMenu();
            return;
        }

        openMenu();
    });

    catalogMenuTrigger?.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();

        if (window.innerWidth < 768) {
            return;
        }

        setCatalogMenuState(!catalogMenuOpen);
    });

    mobileMenuClose?.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        closeMenu();
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', closeMenu);
    });

    mobileCatalogTrigger?.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        toggleMobileCatalog();
    });

    document.addEventListener('click', (event) => {
        if (
            catalogMenuOpen &&
            catalogMenuTrigger &&
            catalogMenuPanel &&
            !catalogMenuTrigger.contains(event.target) &&
            !catalogMenuPanel.contains(event.target)
        ) {
            closeCatalogMenu();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        if (menuOpen) {
            closeMenu();
        }

        if (catalogMenuOpen) {
            closeCatalogMenu();
        }
    });

    window.addEventListener('scroll', updateNavbar, { passive: true });
    window.addEventListener('scroll', updateActiveSection, { passive: true });
    window.addEventListener('resize', () => {
        updateActiveSection();

        if (window.innerWidth < 768) {
            closeCatalogMenu();
            return;
        }

        toggleMobileCatalog(false);
    }, { passive: true });

    updateNavbar();
    updateActiveSection();
};

const initRevealAnimations = () => {
    const revealElements = document.querySelectorAll('.reveal-on-scroll');

    if (!revealElements.length || !('IntersectionObserver' in window)) {
        revealElements.forEach((element) => element.classList.add('revealed'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px',
        }
    );

    revealElements.forEach((element) => observer.observe(element));
};

const initProductScroll = () => {
    document.querySelectorAll('.product-scroll-container').forEach((container) => {
        let isDragging = false;
        let hasExceededDragThreshold = false;
        let startX = 0;
        let startScrollLeft = 0;
        let pendingScrollLeft = 0;
        let frameRequested = false;
        let activePointerId = null;
        const dragThreshold = 8;

        const isInteractiveTarget = (target) =>
            target instanceof Element &&
            target.closest(
                'button, a, input, select, textarea, label, [data-order-url], [data-external-url], [data-product-modal-close]'
            );

        const flushScroll = () => {
            container.scrollLeft = pendingScrollLeft;
            frameRequested = false;
        };

        const queueScroll = () => {
            if (frameRequested) {
                return;
            }

            frameRequested = true;
            window.requestAnimationFrame(flushScroll);
        };

        container.addEventListener('pointerdown', (event) => {
            if (isInteractiveTarget(event.target)) {
                return;
            }

            isDragging = true;
            hasExceededDragThreshold = false;
            startX = event.clientX;
            startScrollLeft = container.scrollLeft;
            pendingScrollLeft = container.scrollLeft;
            activePointerId = event.pointerId;
        });

        container.addEventListener('pointermove', (event) => {
            if (!isDragging) {
                return;
            }

            const deltaX = event.clientX - startX;

            if (!hasExceededDragThreshold) {
                if (Math.abs(deltaX) < dragThreshold) {
                    return;
                }

                hasExceededDragThreshold = true;
                container.classList.add('active-drag');
                container.setPointerCapture(event.pointerId);
            }

            event.preventDefault();
            const walk = deltaX * 1.5;
            pendingScrollLeft = startScrollLeft - walk;
            queueScroll();
        });

        const stopDragging = (event) => {
            if (!isDragging) {
                return;
            }

            isDragging = false;
            hasExceededDragThreshold = false;
            container.classList.remove('active-drag');

            if (event?.pointerId !== undefined && container.hasPointerCapture(event.pointerId)) {
                container.releasePointerCapture(event.pointerId);
            }

            activePointerId = null;
        };

        container.addEventListener('pointerup', stopDragging);
        container.addEventListener('pointercancel', stopDragging);
        container.addEventListener('pointerleave', (event) => {
            if (activePointerId === event.pointerId) {
                stopDragging(event);
            }
        });
    });
};

const initLandingProductButtons = () => {
    const resolveAction = (target) => {
        if (!(target instanceof Element)) {
            return;
        }

        const externalLinkTrigger = target.closest('[data-external-url]');

        if (externalLinkTrigger) {
            const url = externalLinkTrigger.dataset.externalUrl;

            if (url) {
                window.open(url, '_blank');
            }

            return true;
        }

        const orderLinkTrigger = target.closest('[data-order-url]');

        if (orderLinkTrigger) {
            const url = orderLinkTrigger.dataset.orderUrl;

            if (url) {
                window.location.href = url;
            }

            return true;
        }

        return false;
    };

    document.querySelectorAll('[data-order-url], [data-external-url]').forEach((element) => {
        element.addEventListener('touchend', (event) => {
            event.preventDefault();
            event.stopPropagation();
            resolveAction(event.currentTarget);
        });

        element.addEventListener('pointerup', (event) => {
            event.preventDefault();
            event.stopPropagation();
            resolveAction(event.currentTarget);
        });

        element.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            resolveAction(event.currentTarget);
        });
    });
};

const initCounters = () => {
    const counters = document.querySelectorAll('.counter-number[data-target]');

    if (!counters.length) {
        return;
    }

    const animateCounter = (element) => {
        const target = Number.parseInt(element.dataset.target ?? '', 10);

        if (!Number.isFinite(target) || target <= 0) {
            return;
        }

        const duration = 1200;
        const start = performance.now();

        const tick = (timestamp) => {
            const progress = Math.min((timestamp - start) / duration, 1);
            const eased = 1 - (1 - progress) * (1 - progress);
            const value = Math.round(target * eased);

            element.textContent = `${value}+`;

            if (progress < 1) {
                window.requestAnimationFrame(tick);
            }
        };

        window.requestAnimationFrame(tick);
    };

    if (!('IntersectionObserver' in window)) {
        counters.forEach(animateCounter);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                animateCounter(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.5 }
    );

    counters.forEach((counter) => observer.observe(counter));
};

const initGalleryFilter = () => {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const masonryItems = document.querySelectorAll('.masonry-item');

    if (!filterButtons.length || !masonryItems.length) {
        return;
    }

    const setActiveButton = (button) => {
        filterButtons.forEach((item) => {
            const isActive = item === button;
            item.classList.toggle('bg-[#7A1F2B]', isActive);
            item.classList.toggle('text-white', isActive);
            item.classList.toggle('active', isActive);
            item.classList.toggle('bg-white', !isActive);
            item.classList.toggle('text-gray-500', !isActive);
            item.classList.toggle('border', !isActive);
            item.classList.toggle('border-gray-200', !isActive);
            item.classList.toggle('hover:bg-gray-50', !isActive);
        });
    };

    const filterItems = (filterValue) => {
        masonryItems.forEach((item) => {
            const matches =
                filterValue === 'all' || item.getAttribute('data-category') === filterValue;

            item.classList.toggle('masonry-item-hidden', !matches);
            item.hidden = !matches;
        });
    };

    filterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            setActiveButton(button);
            filterItems(button.getAttribute('data-filter') ?? 'all');
        });
    });
};

const initCatalogFilters = () => {
    const root = document.querySelector('[data-catalog-root]');

    if (!(root instanceof HTMLElement)) {
        return;
    }

    const buttons = Array.from(root.querySelectorAll('[data-catalog-filter]'));
    const sections = Array.from(document.querySelectorAll('[data-catalog-section]'));

    if (!buttons.length || !sections.length) {
        return;
    }

    const hoverClasses = ['hover:border-[#CBB39A]', 'hover:text-[#7A1F2B]'];
    const transitionDuration = 160;
    let activeKey = root.dataset.selectedCategory || 'all';
    let isAnimating = false;

    const syncButtons = (activeKey) => {
        buttons.forEach((button) => {
            if (!(button instanceof HTMLElement)) {
                return;
            }

            const isActive = (button.dataset.categoryKey ?? 'all') === activeKey;

            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            button.classList.toggle('border-[#7A1F2B]', isActive);
            button.classList.toggle('bg-[#7A1F2B]', isActive);
            button.classList.toggle('text-white', isActive);
            button.classList.toggle('shadow-[0_12px_26px_rgba(122,31,43,0.18)]', isActive);

            button.classList.toggle('border-[#DCCDBD]', !isActive);
            button.classList.toggle('bg-white', !isActive);
            button.classList.toggle('text-[#6F5D58]', !isActive);
            hoverClasses.forEach((className) => button.classList.toggle(className, !isActive));
        });
    };

    const syncSections = (activeKey) => {
        document.querySelectorAll('[data-catalog-global]').forEach((section) => {
            if (!(section instanceof HTMLElement)) {
                return;
            }

            section.hidden = activeKey !== 'all';
        });

        sections.forEach((section) => {
            if (!(section instanceof HTMLElement)) {
                return;
            }

            const matches =
                activeKey === 'all' || section.dataset.categoryKey === activeKey;

            section.hidden = !matches;
        });
    };

    const getVisibleBlocks = () =>
        [
            ...Array.from(document.querySelectorAll('[data-catalog-global]')),
            ...sections,
        ].filter(
            (section) => section instanceof HTMLElement && !section.hidden
        );

    const syncUrl = (activeKey) => {
        const nextUrl = new URL(window.location.href);

        if (activeKey === 'all') {
            nextUrl.searchParams.delete('category');
        } else {
            nextUrl.searchParams.set('category', activeKey);
        }

        const nextValue = `${nextUrl.pathname}${nextUrl.search}${nextUrl.hash}`;
        window.history.replaceState({}, '', nextValue);
    };

    const applyFilter = (activeKey) => {
        syncButtons(activeKey);
        syncSections(activeKey);
        syncUrl(activeKey);
    };

    const animateFilter = (nextKey) => {
        if (isAnimating || nextKey === activeKey) {
            return;
        }

        isAnimating = true;
        syncButtons(nextKey);

        const visibleBlocks = getVisibleBlocks();
        visibleBlocks.forEach((section) => section.classList.add('catalog-swap-exit'));

        window.setTimeout(() => {
            visibleBlocks.forEach((section) => section.classList.remove('catalog-swap-exit'));
            syncSections(nextKey);
            syncUrl(nextKey);

            const incomingBlocks = getVisibleBlocks();
            incomingBlocks.forEach((section) => {
                section.classList.add('catalog-swap-enter');
                section.querySelectorAll('.product-card').forEach((card) => {
                    card.classList.add('catalog-card-enter');
                });
            });

            window.requestAnimationFrame(() => {
                window.setTimeout(() => {
                    incomingBlocks.forEach((section) => {
                        section.classList.remove('catalog-swap-enter');
                        section.querySelectorAll('.product-card').forEach((card) => {
                            card.classList.remove('catalog-card-enter');
                        });
                    });

                    activeKey = nextKey;
                    isAnimating = false;
                }, 360);
            });
        }, transitionDuration);
    };

    applyFilter(activeKey);

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const nextKey = button.dataset.categoryKey || 'all';
            animateFilter(nextKey);
        });
    });
};

const initCategoryScrollerButtons = () => {
    const buttons = document.querySelectorAll('[data-scroll-products]');

    if (!buttons.length) {
        return;
    }

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const section = button.closest('section');
            const scroller = section?.querySelector('.product-scroll-container');

            if (!scroller) {
                return;
            }

            const step = Math.max(scroller.clientWidth * 0.8, 240);

            scroller.scrollBy({
                left: step,
                behavior: 'smooth',
            });
        });
    });
};

const initAdminSearchForms = () => {
    const forms = document.querySelectorAll('form[data-admin-search-form="true"]');

    if (!forms.length) {
        return;
    }

    forms.forEach((form) => {
        const input = form.querySelector('[data-search-input="true"]');
        const submitButton = form.querySelector('[data-search-submit="true"]');
        const spinner = form.querySelector('[data-search-spinner="true"]');
        const label = form.querySelector('[data-search-label="true"]');
        const hint = form.querySelector('[data-search-hint="true"]');

        const setLoadingState = () => {
            if (!(submitButton instanceof HTMLButtonElement) || submitButton.dataset.loading === 'true') {
                return;
            }

            submitButton.dataset.loading = 'true';
            submitButton.disabled = true;
            submitButton.classList.add('cursor-wait', 'opacity-80');

            if (input instanceof HTMLInputElement) {
                input.readOnly = true;
                input.setAttribute('aria-busy', 'true');
            }

            if (label instanceof HTMLElement) {
                label.textContent = 'Mencari...';
                label.classList.remove('hidden');
            }

            if (hint instanceof HTMLElement) {
                hint.classList.add('hidden');
            }

            if (spinner instanceof HTMLElement) {
                spinner.classList.remove('hidden');
            }
        };

        if (input instanceof HTMLInputElement) {
            input.addEventListener('keydown', (event) => {
                if (event.key !== 'Enter' || event.shiftKey || event.isComposing) {
                    return;
                }

                event.preventDefault();
                setLoadingState();
                form.requestSubmit(submitButton instanceof HTMLButtonElement ? submitButton : undefined);
            });
        }

        form.addEventListener('submit', () => {
            setLoadingState();
        });
    });
};

const initAdminTableSearchForms = () => {
    const forms = document.querySelectorAll('form[data-local-table-search="true"]');

    if (!forms.length) {
        return;
    }

    forms.forEach((form) => {
        const input = form.querySelector('input[name="search"]');

        if (!(input instanceof HTMLInputElement)) {
            return;
        }

        let debounceId = null;

        const submitSearch = () => {
            const nextValue = input.value.trim();

            if (nextValue === (form.dataset.lastSubmitted ?? '')) {
                return;
            }

            form.dataset.lastSubmitted = nextValue;
            form.requestSubmit();
        };

        input.addEventListener('input', () => {
            window.clearTimeout(debounceId);
            debounceId = window.setTimeout(submitSearch, 260);
        });

        input.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();
            window.clearTimeout(debounceId);
            submitSearch();
        });
    });
};

const buildStatusBadge = (status) => {
    const badge = createElement(
        'span',
        'text-xs font-bold px-3 py-1 rounded-full'
    );

    switch (status) {
        case 'UNPAID':
        case 'Menunggu Pembayaran':
            badge.classList.add('text-yellow-600', 'bg-yellow-100');
            badge.textContent = 'Menunggu Pembayaran';
            break;
        case 'PAID':
        case 'Sedang Diproses':
            badge.classList.add('text-blue-600', 'bg-blue-100');
            badge.textContent = 'Sedang Diproses';
            break;
        case 'DELIVERED':
        case 'Siap Dikirim':
            badge.classList.add('text-green-600', 'bg-green-100');
            badge.textContent = 'Siap Dikirim';
            break;
        case 'Selesai':
            badge.classList.add('text-emerald-700', 'bg-emerald-100');
            badge.textContent = 'Selesai';
            break;
        case 'Dibatalkan':
            badge.classList.add('text-rose-700', 'bg-rose-100');
            badge.textContent = 'Dibatalkan';
            break;
        default:
            badge.classList.add('text-gray-600', 'bg-gray-100');
            badge.textContent = status;
            break;
    }

    return badge;
};

const buildTrackingResult = (data) => {
    const fragment = document.createDocumentFragment();

    const header = createElement('div', 'flex items-center gap-3 mb-4');
    const iconWrap = createElement(
        'div',
        'w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0'
    );
    iconWrap.innerHTML =
        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    const titleWrap = createElement('div');
    titleWrap.appendChild(
        createElement('h4', 'text-[#2D1E1E] font-bold text-lg', 'Pesanan Ditemukan')
    );
    titleWrap.appendChild(
        createElement('p', 'text-xs text-gray-500 font-mono', data.order_id)
    );
    header.appendChild(iconWrap);
    header.appendChild(titleWrap);
    fragment.appendChild(header);

    const content = createElement('div', 'border-t border-gray-100 pt-4 space-y-3');

    const productRow = createElement('div', 'flex justify-between items-center gap-4');
    productRow.appendChild(createElement('span', 'text-sm text-gray-500', 'Produk'));
    const productValue = createElement(
        'span',
        'text-sm font-semibold text-[#2D1E1E] text-right truncate max-w-[150px]',
        data.product_name
    );
    productValue.title = data.product_name;
    productRow.appendChild(productValue);

    const statusRow = createElement('div', 'flex justify-between items-center gap-4');
    statusRow.appendChild(createElement('span', 'text-sm text-gray-500', 'Status'));
    statusRow.appendChild(buildStatusBadge(data.status_label || data.status));

    const paymentRow = createElement('div', 'flex justify-between items-center gap-4');
    paymentRow.appendChild(createElement('span', 'text-sm text-gray-500', 'Pembayaran'));
    paymentRow.appendChild(
        createElement('span', 'text-sm font-semibold text-[#2D1E1E] text-right', data.payment_status)
    );

    const totalRow = createElement('div', 'flex justify-between items-center gap-4');
    totalRow.appendChild(createElement('span', 'text-sm text-gray-500', 'Total'));
    totalRow.appendChild(
        createElement('span', 'text-sm font-semibold text-[#2D1E1E] text-right', data.total)
    );

    const deliveryRow = createElement('div', 'flex justify-between items-center gap-4');
    deliveryRow.appendChild(createElement('span', 'text-sm text-gray-500', 'Jadwal Kirim'));
    deliveryRow.appendChild(
        createElement(
            'span',
            'text-sm font-semibold text-[#2D1E1E] text-right',
            `${data.delivery_date} | ${data.delivery_time}`
        )
    );

    content.appendChild(productRow);
    content.appendChild(statusRow);
    content.appendChild(paymentRow);
    content.appendChild(totalRow);
    content.appendChild(deliveryRow);

    if (data.deadline) {
        const deadlineRow = createElement('div', 'flex justify-between items-center gap-4');
        deadlineRow.appendChild(createElement('span', 'text-sm text-gray-500', 'Batas bayar'));
        deadlineRow.appendChild(
            createElement('span', 'text-sm font-semibold text-[#2D1E1E] text-right', data.deadline)
        );
        content.appendChild(deadlineRow);
    }

    fragment.appendChild(content);

    if (Array.isArray(data.timeline) && data.timeline.length > 0) {
        const timelineWrap = createElement('div', 'mt-5');
        timelineWrap.appendChild(
            createElement('p', 'text-xs font-semibold uppercase tracking-[0.18em] text-[#7A1F2B]', 'Progres')
        );

        const timelineList = createElement('div', 'mt-3 flex flex-wrap gap-2');

        data.timeline.forEach((step) => {
            const classes = step.done
                ? 'border-[#d8ead2] bg-[#eef8e9] text-[#416936]'
                : step.active
                  ? 'border-[#ead1c9] bg-[#f9efea] text-[#7A1F2B]'
                  : 'border-gray-200 bg-gray-50 text-gray-500';

            timelineList.appendChild(
                createElement(
                    'span',
                    `inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium ${classes}`,
                    step.label
                )
            );
        });

        timelineWrap.appendChild(timelineList);
        fragment.appendChild(timelineWrap);
    }

    if (data.invoice_url) {
        const actionWrap = createElement('div', 'mt-5 flex flex-wrap gap-3');
        const invoiceLink = document.createElement('a');
        invoiceLink.href = data.invoice_url;
        invoiceLink.className = data.can_continue_payment
            ? 'inline-flex items-center justify-center rounded-full bg-[#7A1F2B] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#65202a]'
            : 'inline-flex items-center justify-center rounded-full border border-[#ead1c9] bg-white px-5 py-3 text-sm font-semibold text-[#7A1F2B] transition hover:bg-[#fbf4ef]';
        invoiceLink.textContent = data.can_continue_payment ? 'Lanjut bayar' : 'Buka invoice';
        actionWrap.appendChild(invoiceLink);

        if (data.payment_method && data.payment_method !== '-') {
            actionWrap.appendChild(
                createElement(
                    'span',
                    'inline-flex items-center rounded-full bg-[#f5efea] px-3 py-2 text-xs font-medium text-[#6b4d49]',
                    `Metode: ${data.payment_method}`
                )
            );
        }

        fragment.appendChild(actionWrap);
    }

    if (data.track_url) {
        const detailWrap = createElement('div', 'mt-3');
        const detailLink = document.createElement('a');
        detailLink.href = data.track_url;
        detailLink.className = 'text-sm font-medium text-[#7A1F2B] underline-offset-4 hover:underline';
        detailLink.textContent = 'Lihat detail tracking';
        detailWrap.appendChild(detailLink);
        fragment.appendChild(detailWrap);
    }

    return fragment;
};

const buildTrackingNotFound = () => {
    const wrapper = createElement(
        'div',
        'flex flex-col items-center justify-center py-4 text-center'
    );
    const iconWrap = createElement(
        'div',
        'w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-500 mb-3'
    );
    iconWrap.innerHTML =
        '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
    wrapper.appendChild(iconWrap);
    wrapper.appendChild(createElement('h4', 'text-[#2D1E1E] font-bold', 'Kode Tidak Ditemukan'));
    wrapper.appendChild(
        createElement(
            'p',
            'text-sm text-gray-500 mt-1',
            'Pastikan Anda memasukkan kode pesanan yang benar (Contoh: SDT-...).'
        )
    );

    return wrapper;
};

const initTracking = () => {
    const input = document.getElementById('trackingInput');
    const button = document.getElementById('trackingBtn');
    const buttonText = document.getElementById('trackingBtnText');
    const spinner = document.getElementById('trackingSpinner');
    const resultBox = document.getElementById('trackingResult');

    if (!input || !button || !buttonText || !spinner || !resultBox) {
        return;
    }

    let isLoading = false;

    const renderResult = (node) => {
        resultBox.replaceChildren(node);
        resultBox.classList.remove('hidden');
        resultBox.classList.add('translate-y-4', 'opacity-0');

        window.requestAnimationFrame(() => {
            resultBox.classList.add('translate-y-0', 'opacity-100');
            resultBox.classList.remove('translate-y-4', 'opacity-0');
        });
    };

    const resetLoadingState = () => {
        isLoading = false;
        buttonText.textContent = 'Lacak Sekarang';
        spinner.classList.add('hidden');
        button.disabled = false;
    };

    const trackOrder = async () => {
        if (isLoading) {
            return;
        }

        const code = input.value.trim().toUpperCase();

        if (!code) {
            input.focus();
            input.classList.add('ring-2', 'ring-red-400');
            window.setTimeout(() => {
                input.classList.remove('ring-2', 'ring-red-400');
            }, 1000);
            return;
        }

        isLoading = true;
        button.disabled = true;
        buttonText.textContent = 'Mencari...';
        spinner.classList.remove('hidden');
        resultBox.classList.add('hidden');

        try {
            const response = await fetch(`/api/track/${encodeURIComponent(code)}`);
            const data = await response.json();

            if (data.found) {
                renderResult(buildTrackingResult(data));
            } else {
                renderResult(buildTrackingNotFound());
            }
        } catch (error) {
            alert('Terjadi kesalahan saat melacak pesanan.');
        } finally {
            resetLoadingState();
        }
    };

    button.addEventListener('click', trackOrder);
    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            trackOrder();
        }
    });

    window.trackOrder = trackOrder;
};

const initProductModal = () => {
    const modal = document.getElementById('productModal');

    if (!modal) {
        return;
    }

    const modalTitle = document.getElementById('modalTitle');
    const modalPrice = document.getElementById('modalPrice');
    const modalImage = document.getElementById('modalImg');
    const modalDescription = document.getElementById('modalDesc');
    const modalOrderButton = document.getElementById('modalOrderBtn');
    const modalButtonText = document.getElementById('modalBtnText');
    const modalButtonIcon = document.getElementById('modalBtnIcon');
    const modalContent = modal.querySelector('[data-product-modal-card]');

    if (
        !modalTitle ||
        !modalPrice ||
        !modalImage ||
        !modalDescription ||
        !modalOrderButton ||
        !modalButtonText ||
        !modalButtonIcon ||
        !modalContent
    ) {
        return;
    }

    const closeProductModal = () => {
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-[0.985]');
        modal.classList.add('hidden');
    };

    const openProductModal = (title, price, image, description, tag = '', isDecor = false) => {
        modalTitle.textContent = title;
        modalPrice.textContent = price;
        modalImage.src = image;
        modalImage.alt = title;
        modalDescription.textContent = description;

        if (isDecor) {
            modalButtonText.textContent = 'Konsultasi Sekarang';
            setButtonIcon(modalButtonIcon, 'whatsapp');
        } else {
            modalButtonText.textContent = 'Pesan';
            setButtonIcon(modalButtonIcon, 'arrow');
        }

        modalOrderButton.onclick = () => {
            closeProductModal();

            if (isDecor) {
                const message = `Halo Sadita,\n\nSaya ingin konsultasi dekorasi.\n\nJenis Dekorasi: ${title}\n\nTanggal Acara:\nWaktu Acara:\n\nLokasi Acara:\n\nKonsep / Tema yang diinginkan:\n(Contoh: elegan, rustic, minimalis, dll)\n\nCatatan tambahan:\n(opsional)\n\nTerima kasih.`;
                window.open(
                    `https://wa.me/62812616155335?text=${encodeURIComponent(message)}`,
                    '_blank'
                );
                return;
            }

            const query = new URLSearchParams({
                product: title,
                price,
                img: image,
                jenis: tag,
            });

            const baseOrderUrl = modal.dataset.modalOrderBaseUrl;

            if (!baseOrderUrl) {
                return;
            }

            window.location.href = `${baseOrderUrl}?${query.toString()}`;
        };

        modal.classList.remove('hidden');

        window.requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-[0.985]');
        });
    };

    modal.querySelectorAll('[data-product-modal-close]').forEach((element) => {
        element.addEventListener('pointerup', (event) => {
            event.preventDefault();
            event.stopPropagation();
            closeProductModal();
        });

        element.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            closeProductModal();
        });
    });

    document.addEventListener('click', (event) => {
        const stopModalTrigger = event.target.closest('[data-stop-modal]');

        if (stopModalTrigger) {
            return;
        }

        const productCard = event.target.closest('[data-product-card]');

        if (!productCard) {
            return;
        }

        openProductModal(
            productCard.dataset.modalTitle ?? '',
            productCard.dataset.modalPrice ?? '',
            productCard.dataset.modalImage ?? '',
            productCard.dataset.modalDesc ?? '',
            productCard.dataset.modalTag ?? '',
            productCard.dataset.modalIsDecor === '1'
        );
    });
};

const initChatbot = () => {
    const wrapper = document.getElementById('chatbot-wrapper');

    if (!wrapper) {
        return;
    }

    const groqApiKey = wrapper.dataset.groqApiKey ?? '';
    const groqModel = wrapper.dataset.groqModel ?? 'llama-3.3-70b-versatile';
    const toggle = document.getElementById('chatbot-toggle');
    const modal = document.getElementById('chatbot-modal');
    const closeButton = document.getElementById('chatbot-close-btn');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const sendButton = document.getElementById('chatbot-send');
    const messages = document.getElementById('chatbot-messages');
    const iconOpen = document.getElementById('chatbot-icon-open');
    const iconClose = document.getElementById('chatbot-icon-close');
    const ping = document.getElementById('chatbot-ping');
    const status = document.getElementById('chatbot-status');
    const label = document.getElementById('chatbot-label');

    if (
        !toggle ||
        !modal ||
        !closeButton ||
        !form ||
        !input ||
        !sendButton ||
        !messages ||
        !iconOpen ||
        !iconClose ||
        !ping ||
        !status
    ) {
        return;
    }

    let chatOpen = false;
    let isTyping = false;
    let resizeFrame = null;
    const systemPrompt = `Kamu adalah "Sadita AI", asisten virtual premium untuk Sadita Decoration, layanan papan bunga, hantaran, dan dekorasi di Padang, Sumatera Barat.

## PERAN UTAMA
Kamu adalah KONSULTAN, bukan mesin penjual. Tugasmu:
1. Pahami kebutuhan customer dengan bertanya: acara apa, untuk siapa, budget berapa.
2. Rekomendasikan produk yang PALING COCOK berdasarkan konteks.
3. Bimbing customer sampai mereka yakin ingin pesan.

## DATA PRODUK
- **Papan Ucapan Standard (Outdoor)**: Rp 350.000 - Rp 850.000+ (untuk pembukaan toko, duka cita, pernikahan, dll)
- **Papan Bunga Mini (Portable/Kado)**: Rp 85.000 - Rp 150.000 (cocok untuk wisuda/hadiah, bahan artificial/akrilik)
- **Papan Bunga Kertas/Akrilik Custom**: Rp 135.000 - Rp 250.000 (kado estetik & modern)
- **Sewa Papan Indoor**: mulai dari Rp 100.000 (untuk keperluan foto)
- **Hantaran (Jasa Hias Saja)**: Rp 30.000 - Rp 50.000 / kotak (jika customer bawa box sendiri)
- **Hantaran (Paket Sewa Box + Hias)**: Rp 250.000 - Rp 800.000 / paket (isi 5-8 kotak. Pilihan bahan: Akrilik, Kayu Jati, Rotan)
- **Dekorasi Akad / Intimate (Sederhana)**: Rp 4.000.000 - Rp 10.000.000
- **Dekorasi Pelaminan (Bagonjong Modern / Mewah)**: Rp 10.000.000 - Rp 30.000.000+ (menyesuaikan bahan bunga segar/artificial dan skala gedung/rumah)

## INFO LAYANAN
- Gratis ongkir area Padang
- Menerima custom request (arahkan ke admin: 089653090248)
- Proses cepat & profesional
- Bisa pesan via WhatsApp
- Rating 4.9 dari 500+ pesanan
- Minimal pemesanan: H-1 (satu hari sebelum acara)
- TIDAK melayani di luar radius area Padang

## ATURAN MENJAWAB
1. Bahasa Indonesia, ramah, hangat, seperti teman curhat.
2. JANGAN langsung dump semua info produk sekaligus. Jawab sesuai pertanyaan saja.
3. Kalau customer bilang "mau pesan" atau bingung pilih, tanya dulu: "Boleh tau ini untuk acara apa, Kak?" atau "Untuk siapa ya, Kak?"
4. Gunakan emoji secukupnya (1-2 per pesan) untuk kesan friendly.
5. Jawab singkat dan padat, maksimal 3-4 kalimat per pesan kecuali diminta detail.
6. Gunakan **bold** untuk nama produk dan harga.
7. Kalau customer mau custom, berikan nomor admin: **089653090248**
8. Kalau ditanya di luar konteks Sadita, arahkan kembali dengan sopan.
9. JANGAN pernah memberikan informasi yang tidak ada di data di atas. Jika tidak tahu, bilang "Untuk detail lebih lanjut, bisa langsung hubungi admin kami ya, Kak."
10. Jangan gunakan format tabel markdown. Gunakan list biasa saja dengan bullet point.

Tolak semua pertanyaan yang tidak berhubungan dengan Sadita dengan menjawab "maaf, kami hanya melayani pertanyaan seputar produk, harga, dan cara pemesanan".`;

    const chatHistory = [{ role: 'system', content: systemPrompt }];

    const toggleChat = () => {
        chatOpen = !chatOpen;

        if (chatOpen) {
            modal.classList.remove('scale-0', 'opacity-0', 'pointer-events-none');
            modal.classList.add('scale-100', 'opacity-100', 'pointer-events-auto');
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
            ping.style.display = 'none';

            if (label) {
                label.classList.add('hidden');
            }

            toggle.classList.remove('px-5', 'gap-2.5');
            toggle.classList.add('w-14');
            input.focus();
            return;
        }

        modal.classList.add('scale-0', 'opacity-0', 'pointer-events-none');
        modal.classList.remove('scale-100', 'opacity-100', 'pointer-events-auto');
        iconOpen.classList.remove('hidden');
        iconClose.classList.add('hidden');

        if (label) {
            label.classList.remove('hidden');
        }

        toggle.classList.remove('w-14');
        toggle.classList.add('px-5', 'gap-2.5');
    };

    const getTimeString = () =>
        new Date().toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
        });

    const parseMarkdown = (text) => {
        let html = text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

        html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        html = html.replace(/(?<!\*)\*(?!\*)(.+?)(?<!\*)\*(?!\*)/g, '<em>$1</em>');
        html = html.replace(/^[\-•]\s+(.+)$/gm, '<li>$1</li>');
        html = html.replace(/((?:<li>.*<\/li>\n?)+)/g, '<ul class="chatbot-list">$1</ul>');
        html = html.replace(/^\d+\.\s+(.+)$/gm, '<li>$1</li>');
        html = html.replace(/(?<!<\/ul>\n?)((?:<li>.*<\/li>\n?)+)/g, (match) => {
            if (match.includes('chatbot-list')) {
                return match;
            }

            return `<ol class="chatbot-list chatbot-ol">${match}</ol>`;
        });
        html = html.replace(/\n/g, '<br>');
        html = html.replace(/<br><li>/g, '<li>');
        html = html.replace(/<\/li><br>/g, '</li>');
        html = html.replace(/<br>(<\/?[uo]l)/g, '$1');
        html = html.replace(/(<\/[uo]l>)<br>/g, '$1');

        return html;
    };

    const appendMessage = (text, isBot) => {
        const chips = document.getElementById('chatbot-chips');

        if (chips && !isBot) {
            chips.style.opacity = '0';
            window.setTimeout(() => chips.remove(), 200);
        }

        const wrapperElement = createElement(
            'div',
            `chatbot-msg ${isBot ? 'chatbot-msg-bot' : 'chatbot-msg-user'}`
        );
        const bubble = createElement(
            'div',
            `chatbot-bubble ${isBot ? 'chatbot-bubble-bot' : 'chatbot-bubble-user'}`
        );

        if (isBot) {
            bubble.innerHTML = parseMarkdown(text);
        } else {
            bubble.textContent = text;
        }

        const time = createElement(
            'div',
            `chatbot-time ${isBot ? 'chatbot-time-bot' : 'chatbot-time-user'}`,
            getTimeString()
        );

        wrapperElement.appendChild(bubble);
        wrapperElement.appendChild(time);
        messages.appendChild(wrapperElement);
        messages.scrollTop = messages.scrollHeight;
    };

    const showTyping = () => {
        const wrapperElement = createElement('div', 'chatbot-msg chatbot-msg-bot');
        wrapperElement.id = 'chatbot-typing';

        const bubble = createElement(
            'div',
            'chatbot-bubble chatbot-bubble-bot chatbot-typing'
        );

        for (let index = 0; index < 3; index += 1) {
            bubble.appendChild(document.createElement('span'));
        }

        wrapperElement.appendChild(bubble);
        messages.appendChild(wrapperElement);
        messages.scrollTop = messages.scrollHeight;
    };

    const removeTyping = () => {
        document.getElementById('chatbot-typing')?.remove();
    };

    const resizeInput = () => {
        if (resizeFrame) {
            window.cancelAnimationFrame(resizeFrame);
        }

        resizeFrame = window.requestAnimationFrame(() => {
            input.style.height = 'auto';
            input.style.height = `${Math.min(input.scrollHeight, 96)}px`;
            resizeFrame = null;
        });
    };

    toggle.addEventListener('click', toggleChat);
    closeButton.addEventListener('click', toggleChat);

    document.addEventListener('click', (event) => {
        if (chatOpen && !modal.contains(event.target) && !toggle.contains(event.target)) {
            toggleChat();
        }
    });

    document.querySelectorAll('.chatbot-chip').forEach((chip) => {
        chip.addEventListener('click', function handleChipClick() {
            input.value = this.dataset.msg ?? '';
            sendButton.disabled = !input.value.trim();
            resizeInput();
            form.requestSubmit();

            const chipsContainer = document.getElementById('chatbot-chips');

            if (chipsContainer) {
                chipsContainer.style.opacity = '0';
                chipsContainer.style.transform = 'translateY(-8px)';
                window.setTimeout(() => chipsContainer.remove(), 300);
            }
        });
    });

    input.addEventListener('input', () => {
        resizeInput();
        sendButton.disabled = !input.value.trim();
    });

    input.addEventListener('focus', () => {
        input.style.borderColor = 'rgba(122,31,43,0.3)';
        input.style.boxShadow = '0 0 0 3px rgba(122,31,43,0.08)';
    });

    input.addEventListener('blur', () => {
        input.style.borderColor = 'rgba(122,31,43,0.1)';
        input.style.boxShadow = 'none';
    });

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();

            if (input.value.trim()) {
                form.requestSubmit();
            }
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const text = input.value.trim();

        if (!text || isTyping) {
            return;
        }

        appendMessage(text, false);
        chatHistory.push({ role: 'user', content: text });
        input.value = '';
        input.style.height = 'auto';
        sendButton.disabled = true;
        isTyping = true;
        status.textContent = 'Mengetik...';
        showTyping();

        if (!groqApiKey) {
            removeTyping();
            appendMessage(
                'Maaf, API key belum dikonfigurasi. Silakan tambahkan GROQ_API_KEY di file .env Anda.',
                true
            );
            isTyping = false;
            status.textContent = 'Online - siap membantu';
            return;
        }

        try {
            const response = await fetch('https://api.groq.com/openai/v1/chat/completions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${groqApiKey}`,
                },
                body: JSON.stringify({
                    model: groqModel,
                    messages: chatHistory,
                    max_tokens: 512,
                    temperature: 0.7,
                }),
            });

            const data = await response.json();
            removeTyping();

            if (data.choices?.[0]?.message?.content) {
                const reply = data.choices[0].message.content;
                appendMessage(reply, true);
                chatHistory.push({ role: 'assistant', content: reply });
            } else {
                appendMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', true);
            }
        } catch (error) {
            removeTyping();
            appendMessage('Gagal terhubung ke server. Periksa koneksi Anda.', true);
        }

        isTyping = false;
        status.textContent = 'Online - siap membantu';
    });
};

onReady(() => {
    initPreloader();
    initNavbar();
    initRevealAnimations();
    initProductScroll();
    initCounters();
    initGalleryFilter();
    initCatalogFilters();
    initCategoryScrollerButtons();
    initLandingProductButtons();
    initAdminSearchForms();
    initAdminTableSearchForms();
    initTracking();
    initProductModal();
    initChatbot();
});
