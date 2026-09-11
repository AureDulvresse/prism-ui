/**
 * HaloUI — Alpine.js registration.
 *
 * Interactive components register a named Alpine.data() factory here and
 * are invoked in Blade as x-data="haloX()". Global, cross-component state
 * (like the active theme, or the toast queue) is registered as an
 * Alpine.store() instead.
 */

import Alpine from 'alpinejs';

const THEMES = ['halo', 'aurora', 'eclipse', 'ember', 'nocturne', 'luma', 'flint'];
const STORAGE_KEY = 'halo-theme';

// Elements a focus trap / roving tabindex should consider stoppable points.
const FOCUSABLE_SELECTOR = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

document.addEventListener('alpine:init', () => {
    Alpine.store('haloTheme', {
        available: THEMES,
        current: localStorage.getItem(STORAGE_KEY) || 'halo',

        set(name) {
            if (!THEMES.includes(name)) {
                return;
            }

            this.current = name;
            document.documentElement.setAttribute('data-theme', name);
            localStorage.setItem(STORAGE_KEY, name);
        },

        init() {
            document.documentElement.setAttribute('data-theme', this.current);
        },
    });

    // Modal: identified by name, opened/closed from anywhere via
    // $dispatch('open-modal', 'name') / $dispatch('close-modal', 'name').
    // A missing detail on close-modal closes every open modal. Traps focus
    // inside the panel while open and returns it to whatever triggered the
    // modal on close, per the WAI-ARIA dialog pattern.
    Alpine.data('haloModal', (name) => ({
        name,
        open: false,
        previouslyFocused: null,

        init() {
            window.addEventListener('open-modal', (event) => {
                if (event.detail === this.name) {
                    this.show();
                }
            });

            window.addEventListener('close-modal', (event) => {
                if (!event.detail || event.detail === this.name) {
                    this.close();
                }
            });
        },

        show() {
            this.previouslyFocused = document.activeElement;
            this.open = true;
            this.$nextTick(() => this.focusFirst());
        },

        close() {
            if (!this.open) {
                return;
            }

            this.open = false;

            if (this.previouslyFocused instanceof HTMLElement) {
                this.previouslyFocused.focus();
            }

            this.previouslyFocused = null;
        },

        focusFirst() {
            const panel = this.$refs.panel;

            if (!panel) {
                return;
            }

            const focusable = panel.querySelectorAll(FOCUSABLE_SELECTOR);

            (focusable[0] ?? panel).focus();
        },

        trapFocus(event) {
            const panel = this.$refs.panel;

            if (!panel) {
                return;
            }

            const focusable = Array.from(panel.querySelectorAll(FOCUSABLE_SELECTOR));

            if (!focusable.length) {
                return;
            }

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },
    }));

    // Dropdown: local open/closed state, closed on escape, on clicking
    // outside, or on selecting an item. Arrow keys move focus between
    // items (WAI-ARIA menu pattern); focus returns to the trigger on close.
    Alpine.data('haloDropdown', () => ({
        open: false,
        previouslyFocused: null,

        toggle() {
            this.open ? this.close() : this.openMenu();
        },

        openMenu() {
            this.previouslyFocused = document.activeElement;
            this.open = true;
            this.$nextTick(() => this.focusFirstItem());
        },

        close() {
            if (!this.open) {
                return;
            }

            this.open = false;

            if (this.previouslyFocused instanceof HTMLElement) {
                this.previouslyFocused.focus();
            }

            this.previouslyFocused = null;
        },

        closeOnItemClick(event) {
            if (event.target.closest('[role="menuitem"]')) {
                this.close();
            }
        },

        menuItems() {
            return Array.from(this.$refs.panel?.querySelectorAll('[role="menuitem"]') ?? []);
        },

        focusFirstItem() {
            this.menuItems()[0]?.focus();
        },

        focusNext() {
            const items = this.menuItems();

            if (!items.length) {
                return;
            }

            const index = items.indexOf(document.activeElement);

            (items[index + 1] ?? items[0]).focus();
        },

        focusPrevious() {
            const items = this.menuItems();

            if (!items.length) {
                return;
            }

            const index = items.indexOf(document.activeElement);

            (items[index - 1] ?? items[items.length - 1]).focus();
        },
    }));

    // Tabs: single active tab tracked by an arbitrary string key. Arrow keys
    // roam between triggers (WAI-ARIA tabs pattern).
    Alpine.data('haloTabs', (active = null) => ({
        active,

        select(tab) {
            this.active = tab;
        },

        isActive(tab) {
            return this.active === tab;
        },

        focusSibling(event, direction) {
            const tabs = Array.from(this.$root.querySelectorAll('[role="tab"]'));
            const index = tabs.indexOf(event.target);
            const next = tabs[index + direction] ?? (direction > 0 ? tabs[0] : tabs[tabs.length - 1]);

            next.focus();
            next.click();
        },
    }));

    // Accordion: tracks which item(s) are open by an arbitrary string key.
    // `multiple` allows more than one open at once; otherwise opening an
    // item closes any other.
    Alpine.data('haloAccordion', (multiple = false) => ({
        multiple,
        openItems: [],

        isOpen(name) {
            return this.openItems.includes(name);
        },

        toggle(name) {
            if (this.isOpen(name)) {
                this.openItems = this.openItems.filter((item) => item !== name);

                return;
            }

            this.openItems = this.multiple ? [...this.openItems, name] : [name];
        },
    }));

    // ToggleGroup: tracks the selected value(s) of a segmented control. `type`
    // is 'single' (value is a string, replaced on each select) or 'multiple'
    // (value is an array, membership toggled on each select), following the
    // WAI-ARIA button/toolbar pattern used per-item (aria-pressed).
    Alpine.data('haloToggleGroup', (type = 'single', initial = null) => ({
        type,
        value: initial,

        isSelected(v) {
            return this.type === 'multiple' ? this.value.includes(v) : this.value === v;
        },

        select(v) {
            if (this.type === 'multiple') {
                this.value = this.isSelected(v) ? this.value.filter((item) => item !== v) : [...this.value, v];

                return;
            }

            this.value = v;
        },
    }));

    // Stepper: tracks the active step by its 1-indexed position. Steps before
    // `current` are complete, the step equal to `current` is active, and later
    // steps are pending.
    Alpine.data('haloStepper', (current = 1) => ({
        current,

        isActive(step) {
            return step === this.current;
        },

        isComplete(step) {
            return step < this.current;
        },
    }));

    // Tooltip: shown on hover or focus of its trigger, hidden on the
    // opposite. Sets aria-describedby on the trigger's first element so
    // assistive tech announces the tooltip text, per the WAI-ARIA tooltip
    // pattern (no focus trap or dismiss key needed — it's not interactive).
    Alpine.data('haloTooltip', (id) => ({
        open: false,

        init() {
            this.$refs.trigger.firstElementChild?.setAttribute('aria-describedby', id);
        },

        show() {
            this.open = true;
        },

        hide() {
            this.open = false;
        },
    }));

    // Popover: like Dropdown but for arbitrary rich content rather than a
    // list of menu items — no role="menu"/arrow-key roving focus, just
    // open/close on trigger click, escape, or an outside click, with focus
    // returned to the trigger on close.
    Alpine.data('haloPopover', () => ({
        open: false,
        previouslyFocused: null,

        toggle() {
            this.open ? this.close() : this.openPanel();
        },

        openPanel() {
            this.previouslyFocused = document.activeElement;
            this.open = true;
            this.$nextTick(() => this.focusFirst());
        },

        close() {
            if (!this.open) {
                return;
            }

            this.open = false;

            if (this.previouslyFocused instanceof HTMLElement) {
                this.previouslyFocused.focus();
            }

            this.previouslyFocused = null;
        },

        focusFirst() {
            const panel = this.$refs.panel;

            if (!panel) {
                return;
            }

            const focusable = panel.querySelectorAll(FOCUSABLE_SELECTOR);

            (focusable[0] ?? panel).focus();
        },
    }));

    // Combobox: a text input that filters a server-rendered list of options
    // client-side as you type (no AJAX/dynamic loading). `selected`/`selectedLabel`
    // back a hidden form input and the input's displayed text respectively;
    // `matches()` is the case-insensitive substring check each option's x-show
    // uses. Open/close/outside-click/escape follow the same discipline as
    // haloPopover, minus roving arrow-key focus (kept minimal for v1).
    Alpine.data('haloCombobox', (initialValue = null) => ({
        open: false,
        query: '',
        selected: initialValue,
        selectedLabel: '',

        matches(text, query) {
            return text.toLowerCase().includes(query.toLowerCase());
        },

        select(value, label) {
            this.selected = value;
            this.selectedLabel = label;
            this.query = '';
            this.close();
        },

        toggle() {
            this.open ? this.close() : this.openPanel();
        },

        openPanel() {
            this.open = true;
            this.query = '';
        },

        close() {
            this.open = false;
        },
    }));

    // Select: a custom-styled trigger + role="listbox" panel, replacing the
    // native <select> popup (which a browser renders itself and can't be
    // restyled to match a themed, rounded field). `selected`/`selectedLabel`
    // back a hidden form input and the trigger's displayed text. Arrow keys
    // roam between options (WAI-ARIA listbox pattern); Enter/click selects,
    // escape/outside-click closes with focus returned to the trigger.
    Alpine.data('haloSelect', (initialValue = null, initialLabel = null) => ({
        open: false,
        selected: initialValue,
        selectedLabel: initialLabel,
        previouslyFocused: null,

        toggle() {
            this.open ? this.close() : this.openPanel();
        },

        openPanel() {
            this.previouslyFocused = document.activeElement;
            this.open = true;
            this.$nextTick(() => this.focusSelected());
        },

        close() {
            if (!this.open) {
                return;
            }

            this.open = false;

            if (this.previouslyFocused instanceof HTMLElement) {
                this.previouslyFocused.focus();
            }

            this.previouslyFocused = null;
        },

        select(value, label) {
            this.selected = value;
            this.selectedLabel = label;
            this.close();
        },

        isSelected(value) {
            return this.selected === value;
        },

        options() {
            return Array.from(this.$refs.panel?.querySelectorAll('[role="option"]') ?? []);
        },

        focusSelected() {
            const options = this.options();
            const current = options.find((option) => option.dataset.value === String(this.selected));

            (current ?? options[0])?.focus();
        },

        focusNext() {
            const options = this.options();

            if (!options.length) {
                return;
            }

            const index = options.indexOf(document.activeElement);

            (options[index + 1] ?? options[0]).focus();
        },

        focusPrevious() {
            const options = this.options();

            if (!options.length) {
                return;
            }

            const index = options.indexOf(document.activeElement);

            (options[index - 1] ?? options[options.length - 1]).focus();
        },
    }));

    // Rating: server-rendered stars (max is known at render time), Alpine only
    // tracks the committed value and a hover preview so pointer users can see
    // a rating before committing to it.
    Alpine.data('haloRating', (initial, max) => ({
        value: initial,
        max,
        hovered: 0,

        set(v) {
            this.value = v;
        },

        hover(v) {
            this.hovered = v;
        },

        unhover() {
            this.hovered = 0;
        },
    }));

    // AlertDialog: a stricter Modal variant for confirmations that must not be
    // dismissed accidentally. Deliberately its own factory (not an `alertMode`
    // flag on haloModal) and its own open-alert-dialog/close-alert-dialog
    // events (not open-modal/close-modal), so a stray close-modal dispatch
    // elsewhere in an app can never dismiss an alert dialog, and no future
    // change to Modal (e.g. adding a new dismiss path) can regress this
    // component's "must pick an explicit action" guarantee. Shares the same
    // focus-trap/focus-restore mechanics as haloModal.
    Alpine.data('haloAlertDialog', (name) => ({
        name,
        open: false,
        previouslyFocused: null,

        init() {
            window.addEventListener('open-alert-dialog', (event) => {
                if (event.detail === this.name) {
                    this.show();
                }
            });

            window.addEventListener('close-alert-dialog', (event) => {
                if (!event.detail || event.detail === this.name) {
                    this.close();
                }
            });
        },

        show() {
            this.previouslyFocused = document.activeElement;
            this.open = true;
            this.$nextTick(() => this.focusFirst());
        },

        close() {
            if (!this.open) {
                return;
            }

            this.open = false;

            if (this.previouslyFocused instanceof HTMLElement) {
                this.previouslyFocused.focus();
            }

            this.previouslyFocused = null;
        },

        focusFirst() {
            const panel = this.$refs.panel;

            if (!panel) {
                return;
            }

            const focusable = panel.querySelectorAll(FOCUSABLE_SELECTOR);

            (focusable[0] ?? panel).focus();
        },

        trapFocus(event) {
            const panel = this.$refs.panel;

            if (!panel) {
                return;
            }

            const focusable = Array.from(panel.querySelectorAll(FOCUSABLE_SELECTOR));

            if (!focusable.length) {
                return;
            }

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },
    }));

    // FileUpload: a native <input type="file"> hidden behind a styled
    // drop zone. Removing a file rebuilds the input's FileList via
    // DataTransfer — a plain JS array can't be reassigned to input.files.
    Alpine.data('haloFileUpload', () => ({
        dragging: false,
        files: [],

        change(event) {
            this.setFiles(event.target.files);
        },

        drop(event) {
            this.dragging = false;
            this.$refs.input.files = event.dataTransfer.files;
            this.setFiles(event.dataTransfer.files);
        },

        setFiles(fileList) {
            this.files = Array.from(fileList);
        },

        remove(file) {
            this.files = this.files.filter((candidate) => candidate !== file);
            this.syncInput();
        },

        syncInput() {
            const transfer = new DataTransfer();
            this.files.forEach((file) => transfer.items.add(file));
            this.$refs.input.files = transfer.files;
        },

        formatSize(bytes) {
            if (bytes < 1024) return `${bytes} B`;
            if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;

            return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
        },
    }));

    // ImageUpload: same drop-zone/DataTransfer mechanics as FileUpload, plus
    // an object-URL preview per file. Revokes each preview on removal to
    // avoid leaking blob URLs for images the user decided not to keep.
    Alpine.data('haloImageUpload', () => ({
        dragging: false,
        files: [],

        change(event) {
            this.setFiles(event.target.files);
        },

        drop(event) {
            this.dragging = false;
            this.$refs.input.files = event.dataTransfer.files;
            this.setFiles(event.dataTransfer.files);
        },

        setFiles(fileList) {
            this.files = Array.from(fileList).map((file) => {
                file.preview = URL.createObjectURL(file);

                return file;
            });
        },

        remove(file) {
            URL.revokeObjectURL(file.preview);
            this.files = this.files.filter((candidate) => candidate !== file);

            const transfer = new DataTransfer();
            this.files.forEach((candidate) => transfer.items.add(candidate));
            this.$refs.input.files = transfer.files;
        },
    }));

    // Drawer: identified by name, opened/closed from anywhere via
    // $dispatch('open-drawer', 'name') / $dispatch('close-drawer', 'name').
    // A missing detail on close-drawer closes every open drawer. Traps focus
    // inside the panel while open and returns it to whatever triggered the
    // drawer on close, per the WAI-ARIA dialog pattern. Same mechanics as
    // haloModal — Drawer just slides in from a screen edge instead of fading
    // in centered.
    Alpine.data('haloDrawer', (name) => ({
        name,
        open: false,
        previouslyFocused: null,

        init() {
            window.addEventListener('open-drawer', (event) => {
                if (event.detail === this.name) {
                    this.show();
                }
            });

            window.addEventListener('close-drawer', (event) => {
                if (!event.detail || event.detail === this.name) {
                    this.close();
                }
            });
        },

        show() {
            this.previouslyFocused = document.activeElement;
            this.open = true;
            this.$nextTick(() => this.focusFirst());
        },

        close() {
            if (!this.open) {
                return;
            }

            this.open = false;

            if (this.previouslyFocused instanceof HTMLElement) {
                this.previouslyFocused.focus();
            }

            this.previouslyFocused = null;
        },

        focusFirst() {
            const panel = this.$refs.panel;

            if (!panel) {
                return;
            }

            const focusable = panel.querySelectorAll(FOCUSABLE_SELECTOR);

            (focusable[0] ?? panel).focus();
        },

        trapFocus(event) {
            const panel = this.$refs.panel;

            if (!panel) {
                return;
            }

            const focusable = Array.from(panel.querySelectorAll(FOCUSABLE_SELECTOR));

            if (!focusable.length) {
                return;
            }

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },
    }));

    // Collapsible: a single, standalone disclosure section — the same
    // open/close mechanic as one Accordion item, simplified to a single
    // boolean since there's no group and no `multiple` concept. The trigger
    // and content ids that link them (aria-controls/aria-labelledby) are
    // generated via Alpine's $id() magic, scoped by the x-id declared on
    // collapsible/index.blade.php — no extra state needed here for that.
    Alpine.data('haloCollapsible', (open = false) => ({
        open,

        toggle() {
            this.open = !this.open;
        },
    }));

    // Command: a Cmd+K-style palette — Modal's focus-trap/focus-restore shell,
    // opened/closed from anywhere via $dispatch('open-command') /
    // $dispatch('close-command') (no `name`, since a page typically has one
    // palette). The search input keeps real DOM focus throughout, so unlike
    // haloDropdown/haloSelect the "roving focus" highlight is a virtual
    // activeIndex over the currently visible [role="option"] elements rather
    // than actual element.focus() calls. options() reuses haloCombobox's
    // matches(text, query) substring check to compute which options are
    // currently visible, so filtering stays in sync with each item's own
    // x-show without depending on DOM style flush timing.
    Alpine.data('haloCommand', () => ({
        open: false,
        query: '',
        activeIndex: 0,
        previouslyFocused: null,

        init() {
            window.addEventListener('open-command', () => this.show());
            window.addEventListener('close-command', () => this.close());
        },

        show() {
            this.previouslyFocused = document.activeElement;
            this.open = true;
            this.query = '';
            this.activeIndex = 0;
            this.$nextTick(() => this.$refs.input?.focus());
        },

        close() {
            if (!this.open) {
                return;
            }

            this.open = false;

            if (this.previouslyFocused instanceof HTMLElement) {
                this.previouslyFocused.focus();
            }

            this.previouslyFocused = null;
        },

        matches(text, query) {
            return text.toLowerCase().includes(query.toLowerCase());
        },

        search() {
            this.activeIndex = 0;
        },

        options() {
            return Array.from(this.$refs.panel?.querySelectorAll('[role="option"]') ?? [])
                .filter((el) => this.matches(el.textContent, this.query));
        },

        hasResults() {
            return this.options().length > 0;
        },

        hasVisibleItems(container) {
            return Array.from(container.querySelectorAll('[role="option"]'))
                .some((el) => this.matches(el.textContent, this.query));
        },

        isActive(el) {
            return this.options()[this.activeIndex] === el;
        },

        setActive(el) {
            const index = this.options().indexOf(el);

            if (index !== -1) {
                this.activeIndex = index;
            }
        },

        moveActive(delta) {
            const items = this.options();

            if (!items.length) {
                return;
            }

            this.activeIndex = (this.activeIndex + delta + items.length) % items.length;
            items[this.activeIndex].scrollIntoView({ block: 'nearest' });
        },

        selectActive() {
            this.options()[this.activeIndex]?.click();
        },

        closeOnItemClick(event) {
            if (event.target.closest('[role="option"]')) {
                this.close();
            }
        },

        trapFocus(event) {
            const panel = this.$refs.panel;

            if (!panel) {
                return;
            }

            const focusable = Array.from(panel.querySelectorAll(FOCUSABLE_SELECTOR));

            if (!focusable.length) {
                return;
            }

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },
    }));

    // Context Menu: like Dropdown, but opens via a right-click anywhere on its
    // trigger area instead of a left-click, and is positioned at the cursor
    // instead of anchored below the trigger. Reuses Dropdown's arrow-key roving
    // focus / escape / outside-click / focus-return behavior; position is
    // clamped on open so the panel never renders past the right/bottom edge of
    // the viewport.
    Alpine.data('haloContextMenu', () => ({
        open: false,
        previouslyFocused: null,
        position: { top: '0px', left: '0px' },

        openMenu(event) {
            this.previouslyFocused = document.activeElement;
            this.open = true;
            this.position = { top: `${event.clientY}px`, left: `${event.clientX}px` };

            this.$nextTick(() => {
                this.clampPosition();
                this.focusFirstItem();
            });
        },

        close() {
            if (!this.open) {
                return;
            }

            this.open = false;

            if (this.previouslyFocused instanceof HTMLElement) {
                this.previouslyFocused.focus();
            }

            this.previouslyFocused = null;
        },

        clampPosition() {
            const panel = this.$refs.panel;

            if (!panel) {
                return;
            }

            const maxLeft = window.innerWidth - panel.offsetWidth;
            const maxTop = window.innerHeight - panel.offsetHeight;

            const left = Math.max(0, Math.min(parseFloat(this.position.left), maxLeft));
            const top = Math.max(0, Math.min(parseFloat(this.position.top), maxTop));

            this.position = { top: `${top}px`, left: `${left}px` };
        },

        closeOnItemClick(event) {
            if (event.target.closest('[role="menuitem"]')) {
                this.close();
            }
        },

        menuItems() {
            return Array.from(this.$refs.panel?.querySelectorAll('[role="menuitem"]') ?? []);
        },

        focusFirstItem() {
            this.menuItems()[0]?.focus();
        },

        focusNext() {
            const items = this.menuItems();

            if (!items.length) {
                return;
            }

            const index = items.indexOf(document.activeElement);

            (items[index + 1] ?? items[0]).focus();
        },

        focusPrevious() {
            const items = this.menuItems();

            if (!items.length) {
                return;
            }

            const index = items.indexOf(document.activeElement);

            (items[index - 1] ?? items[items.length - 1]).focus();
        },
    }));

    // HoverCard: like Tooltip (hover/focus shows, aria-describedby wires the
    // trigger to the content) but for rich anchored content instead of a short
    // text hint, per Popover's panel structure. Open/close are debounced with
    // separate delays so a quick mouse pass across the trigger doesn't flicker
    // it open, and moving from the trigger into the card content doesn't
    // immediately close it.
    Alpine.data('haloHoverCard', (id, openDelay = 700, closeDelay = 300) => ({
        open: false,
        openDelay,
        closeDelay,
        openTimer: null,
        closeTimer: null,

        init() {
            this.$refs.trigger.firstElementChild?.setAttribute('aria-describedby', id);
        },

        show() {
            clearTimeout(this.closeTimer);
            this.openTimer = setTimeout(() => {
                this.open = true;
            }, this.openDelay);
        },

        hide() {
            clearTimeout(this.openTimer);
            this.closeTimer = setTimeout(() => {
                this.open = false;
            }, this.closeDelay);
        },
    }));

    // Calendar: a month-grid date picker primitive. All date math (which days
    // belong to the displayed month, today, month navigation) happens here in
    // JS — the grid re-renders instantly when navigating months, with no
    // server round-trip. Dates are tracked as plain 'YYYY-MM-DD' strings
    // throughout, including min/max, so bounds-checking is a lexical string
    // comparison rather than juggling Date objects/timezones. Weeks start on
    // Monday (ISO-8601). Follows the WAI-ARIA grid pattern for date pickers:
    // role="grid"/"row"/"gridcell", a single roving tabindex tracking the
    // focused day, arrow keys to move focus by day/week, Home/End for the
    // start/end of the focused day's week, and Enter/Space to select it.
    // Selecting a date doesn't write to a form field itself — it dispatches
    // `calendar-change` (detail = the picked date string) for a consumer to
    // wire into a hidden input; see docs/calendar.md.
    Alpine.data('haloCalendar', (value = null, min = null, max = null) => {
        const now = new Date();
        const today = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
        const initial = value || today;
        const [initialYear, initialMonth] = initial.split('-').map(Number);

        return {
            selected: value,
            min,
            max,
            focused: initial,
            viewYear: initialYear,
            viewMonth: initialMonth - 1,

            monthNames: [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December',
            ],

            pad(n) {
                return String(n).padStart(2, '0');
            },

            formatDate(date) {
                return `${date.getFullYear()}-${this.pad(date.getMonth() + 1)}-${this.pad(date.getDate())}`;
            },

            parseDate(dateString) {
                const [year, month, day] = dateString.split('-').map(Number);

                return new Date(year, month - 1, day);
            },

            today() {
                return this.formatDate(new Date());
            },

            addDays(dateString, amount) {
                const date = this.parseDate(dateString);
                date.setDate(date.getDate() + amount);

                return this.formatDate(date);
            },

            isDisabled(dateString) {
                return (this.min && dateString < this.min) || (this.max && dateString > this.max);
            },

            monthLabel() {
                return `${this.monthNames[this.viewMonth]} ${this.viewYear}`;
            },

            dayLabel(dateString) {
                const date = this.parseDate(dateString);

                return `${this.monthNames[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
            },

            previousMonth() {
                this.viewMonth -= 1;

                if (this.viewMonth < 0) {
                    this.viewMonth = 11;
                    this.viewYear -= 1;
                }
            },

            nextMonth() {
                this.viewMonth += 1;

                if (this.viewMonth > 11) {
                    this.viewMonth = 0;
                    this.viewYear += 1;
                }
            },

            weeks() {
                const firstOfMonth = new Date(this.viewYear, this.viewMonth, 1);
                const startOffset = (firstOfMonth.getDay() + 6) % 7;
                const gridStart = new Date(this.viewYear, this.viewMonth, 1 - startOffset);
                const todayString = this.today();

                const days = [];

                for (let i = 0; i < 42; i++) {
                    const date = new Date(gridStart.getFullYear(), gridStart.getMonth(), gridStart.getDate() + i);
                    const dateString = this.formatDate(date);

                    days.push({
                        date: dateString,
                        day: date.getDate(),
                        currentMonth: date.getMonth() === this.viewMonth,
                        isToday: dateString === todayString,
                        disabled: this.isDisabled(dateString),
                    });
                }

                const weeks = [];

                for (let i = 0; i < days.length; i += 7) {
                    weeks.push(days.slice(i, i + 7));
                }

                return weeks;
            },

            select(dateString) {
                if (this.isDisabled(dateString)) {
                    return;
                }

                this.selected = dateString;
                this.focused = dateString;
                this.$dispatch('calendar-change', dateString);
            },

            focus(dateString) {
                this.focused = dateString;
                this.viewYear = Number(dateString.slice(0, 4));
                this.viewMonth = Number(dateString.slice(5, 7)) - 1;
                this.$nextTick(() => this.$root.querySelector(`[data-date="${dateString}"]`)?.focus());
            },

            moveFocus(amount) {
                this.focus(this.addDays(this.focused, amount));
            },

            focusStartOfWeek() {
                const weekday = (this.parseDate(this.focused).getDay() + 6) % 7;

                this.moveFocus(-weekday);
            },

            focusEndOfWeek() {
                const weekday = (this.parseDate(this.focused).getDay() + 6) % 7;

                this.moveFocus(6 - weekday);
            },
        };
    });

    // NumberInput: a quantity stepper wrapping a native <input type="number">.
    // decrement()/increment() don't just update Alpine state — they write the
    // new value onto the real <input> element and dispatch a genuine `input`
    // event, so wire:model/x-model/plain form submission see the change
    // exactly as if it had been typed. atMin/atMax are getters off `value` so
    // the buttons' :disabled bindings update as the user types too, not just
    // when the buttons themselves are clicked.
    Alpine.data('haloNumberInput', ({ value, min, max, step }) => ({
        value,
        min,
        max,
        step,

        get atMin() {
            return this.min !== null && this.value !== null && Number(this.value) <= Number(this.min);
        },

        get atMax() {
            return this.max !== null && this.value !== null && Number(this.value) >= Number(this.max);
        },

        clamp(candidate) {
            if (this.min !== null) candidate = Math.max(candidate, Number(this.min));
            if (this.max !== null) candidate = Math.min(candidate, Number(this.max));

            return candidate;
        },

        apply(newValue) {
            this.value = newValue;
            this.$refs.input.value = newValue;
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
        },

        decrement() {
            const current = this.value !== null ? Number(this.value) : 0;
            this.apply(this.clamp(current - Number(this.step)));
        },

        increment() {
            const current = this.value !== null ? Number(this.value) : 0;
            this.apply(this.clamp(current + Number(this.step)));
        },

        sync(event) {
            this.value = event.target.value === '' ? null : Number(event.target.value);
        },
    }));

    // Toast: a single global queue rendered by <x-halo::toast/>. Push from
    // anywhere with $store.haloToast.push('Saved!', 'success').
    Alpine.store('haloToast', {
        items: [],

        push(message, variant = 'info', duration = 4000) {
            const id = `${Date.now()}-${Math.random().toString(36).slice(2)}`;

            this.items.push({ id, message, variant });

            if (duration) {
                setTimeout(() => this.remove(id), duration);
            }

            return id;
        },

        remove(id) {
            this.items = this.items.filter((item) => item.id !== id);
        },
    });
});

if (typeof window !== 'undefined' && !window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}
