<?php
// app/Views/categories/index.php - Windows Desktop Style
$this->setData(['title' => 'Cikkcsoportok']);
?>

<style>
/* Windows Style for Categories */
.win-toolbar {
    background-color: #F0F0F0;
    border-top: 1px solid #FFFFFF;
    border-bottom: 1px solid #848484;
    padding: 4px;
    display: flex;
    gap: 0;
    margin-bottom: 8px;
}

.win-toolbar-btn {
    background-color: #F0F0F0;
    border: 1px solid #F0F0F0;
    padding: 4px 8px;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #000;
    text-decoration: none;
    margin-right: 2px;
}

.win-toolbar-btn:hover {
    border: 1px solid #0078D7;
    background-color: #E5F1FB;
    color: #000;
    text-decoration: none;
}

/* Tree View Styles */
.win-tree-wrapper {
    background-color: #FFFFFF;
    border: 1px solid #7F7F7F;
    overflow: auto;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    font-size: 11px;
}

.win-tree {
    padding: 4px;
}

.win-tree-item {
    user-select: none;
    cursor: pointer;
}

.win-tree-row {
    display: flex;
    align-items: center;
    padding: 2px 0;
    border: 1px solid transparent;
}

.win-tree-row:hover {
    background-color: #E8F2FE;
    border: 1px solid #7DA2CE;
}

.win-tree-row.selected {
    background-color: #0078D7;
    color: white;
}

.win-tree-indent {
    display: inline-block;
    width: 16px;
}

.win-tree-toggle {
    display: inline-flex;
    width: 16px;
    height: 16px;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #666;
}

.win-tree-toggle:hover {
    color: #000;
}

.win-tree-icon {
    display: inline-flex;
    width: 16px;
    height: 16px;
    align-items: center;
    justify-content: center;
    margin: 0 4px;
}

.win-tree-text {
    flex: 1;
    padding: 0 4px;
}

.win-tree-stats {
    display: flex;
    gap: 16px;
    padding-right: 8px;
    font-size: 10px;
    color: #666;
}

.win-tree-row.selected .win-tree-stats {
    color: #E0E0E0;
}

.win-tree-children {
    display: none;
}

.win-tree-item.expanded > .win-tree-children {
    display: block;
}

/* Empty state */
.win-empty-state {
    text-align: center;
    padding: 40px;
    color: #666;
    font-size: 12px;
}

/* Info panel */
.win-info-panel {
    background-color: #F0F0F0;
    border: 1px solid #D4D0C8;
    padding: 8px;
    margin-top: 8px;
    font-size: 11px;
}

.win-page-header {
    background-color: #FFFFFF;
    border: 1px solid #D4D0C8;
    padding: 8px 12px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 style="font-size: 14px; font-weight: bold; color: #000; margin: 0; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-folder-tree"></i>
        Cikkcsoportok
    </h1>
</div>

<!-- Windows Toolbar -->
<div class="win-toolbar">
    <?php if (\Core\Auth::hasPermission('part.create')): ?>
    <a href="<?= $this->url('categories/create') ?>" class="win-toolbar-btn">
        <i class="fas fa-plus"></i> Új csoport
    </a>
    <?php endif; ?>
    
    <button class="win-toolbar-btn" onclick="editCategory()">
        <i class="fas fa-edit"></i> Szerkesztés
    </button>
    
    <button class="win-toolbar-btn" onclick="deleteCategory()">
        <i class="fas fa-trash"></i> Törlés
    </button>
    
    <button class="win-toolbar-btn" onclick="expandAll()">
        <i class="fas fa-expand"></i> Mind kinyit
    </button>
    
    <button class="win-toolbar-btn" onclick="collapseAll()">
        <i class="fas fa-compress"></i> Mind bezár
    </button>
    
    <button class="win-toolbar-btn" onclick="viewParts()">
        <i class="fas fa-list"></i> Tételek
    </button>
</div>

<!-- Tree View -->
<div class="win-tree-wrapper">
    <div class="win-tree" id="categoryTree">
        <?php if (empty($categories)): ?>
            <div class="win-empty-state">
                <i class="fas fa-folder-tree" style="font-size: 48px; color: #DDD; display: block; margin-bottom: 16px;"></i>
                Nincs még cikkcsoport létrehozva
            </div>
        <?php else: ?>
            <?php
            function renderTree($categories, $level = 0) {
                foreach ($categories as $category): ?>
                    <div class="win-tree-item <?= !empty($category['children']) ? 'has-children' : '' ?>" 
                         data-id="<?= $category['id'] ?>"
                         data-level="<?= $level ?>">
                        <div class="win-tree-row" onclick="selectCategory(this, event)">
                            <?php for ($i = 0; $i < $level; $i++): ?>
                                <span class="win-tree-indent"></span>
                            <?php endfor; ?>
                            
                            <?php if (!empty($category['children'])): ?>
                                <span class="win-tree-toggle" onclick="toggleCategory(this, event)">
                                    <i class="fas fa-caret-right"></i>
                                </span>
                            <?php else: ?>
                                <span class="win-tree-toggle"></span>
                            <?php endif; ?>
                            
                            <span class="win-tree-icon">
                                <?php if (!empty($category['children'])): ?>
                                    <i class="fas fa-folder" style="color: #FFC107;"></i>
                                <?php else: ?>
                                    <i class="fas fa-folder-open" style="color: #FFD54F;"></i>
                                <?php endif; ?>
                            </span>
                            
                            <span class="win-tree-text">
                                <strong><?= htmlspecialchars($category['name']) ?></strong>
                                <?php if ($category['code']): ?>
                                    <span style="color: #666; font-size: 10px;">[<?= htmlspecialchars($category['code']) ?>]</span>
                                <?php endif; ?>
                            </span>
                            
                            <span class="win-tree-stats">
                                <span title="Közvetlen tételek">
                                    <i class="fas fa-cog"></i> <?= $category['direct_parts'] ?>
                                </span>
                                <span title="Összes tétel (alcsoportokkal)">
                                    <i class="fas fa-layer-group"></i> <?= $category['total_parts'] ?>
                                </span>
                            </span>
                        </div>
                        
                        <?php if (!empty($category['children'])): ?>
                            <div class="win-tree-children">
                                <?php renderTree($category['children'], $level + 1); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach;
            }
            renderTree($categories);
            ?>
        <?php endif; ?>
    </div>
</div>

<!-- Info Panel -->
<div class="win-info-panel">
    <i class="fas fa-info-circle"></i> 
    A cikkcsoportok segítségével hierarchikus struktúrába rendezheti az alkatrészeket és szolgáltatásokat. 
    Kattintson duplán egy csoportra a tételek megtekintéséhez.
</div>

<script>
let selectedCategory = null;

// Toggle category expand/collapse
function toggleCategory(toggle, event) {
    event.stopPropagation();
    const item = toggle.closest('.win-tree-item');
    const icon = toggle.querySelector('i');
    
    if (item.classList.contains('expanded')) {
        item.classList.remove('expanded');
        icon.classList.remove('fa-caret-down');
        icon.classList.add('fa-caret-right');
    } else {
        item.classList.add('expanded');
        icon.classList.remove('fa-caret-right');
        icon.classList.add('fa-caret-down');
    }
}

// Select category
function selectCategory(row, event) {
    if (event.target.closest('.win-tree-toggle')) {
        return;
    }
    
    // Remove previous selection
    document.querySelectorAll('.win-tree-row.selected').forEach(r => {
        r.classList.remove('selected');
    });
    
    // Add selection
    row.classList.add('selected');
    selectedCategory = row.closest('.win-tree-item').dataset.id;
    
    // Double click to view parts
    if (event.detail === 2) {
        viewParts();
    }
}

// Get selected category ID
function getSelectedId() {
    return selectedCategory;
}

// Toolbar functions
function editCategory() {
    const id = getSelectedId();
    if (id) {
        window.location.href = '<?= $this->url('categories') ?>/' + id + '/edit';
    } else {
        alert('Válasszon ki egy cikkcsoportot!');
    }
}

function deleteCategory() {
    const id = getSelectedId();
    if (id) {
        if (confirm('Biztosan törölni szeretné a kiválasztott cikkcsoportot?\n\nCsak üres csoport törölhető!')) {
            // Create delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= $this->url('categories') ?>/' + id + '/delete';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= CSRF_TOKEN_NAME ?>';
            csrfInput.value = '<?= \Core\Auth::csrfToken() ?>';
            
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    } else {
        alert('Válasszon ki egy cikkcsoportot!');
    }
}

function viewParts() {
    const id = getSelectedId();
    if (id) {
        window.location.href = '<?= $this->url('categories') ?>/' + id + '/parts';
    } else {
        alert('Válasszon ki egy cikkcsoportot!');
    }
}

function expandAll() {
    document.querySelectorAll('.win-tree-item.has-children').forEach(item => {
        item.classList.add('expanded');
        const icon = item.querySelector('.win-tree-toggle i');
        if (icon) {
            icon.classList.remove('fa-caret-right');
            icon.classList.add('fa-caret-down');
        }
    });
}

function collapseAll() {
    document.querySelectorAll('.win-tree-item.has-children').forEach(item => {
        item.classList.remove('expanded');
        const icon = item.querySelector('.win-tree-toggle i');
        if (icon) {
            icon.classList.remove('fa-caret-down');
            icon.classList.add('fa-caret-right');
        }
    });
}

// Select first category by default
document.addEventListener('DOMContentLoaded', function() {
    const firstRow = document.querySelector('.win-tree-row');
    if (firstRow) {
        selectCategory(firstRow, { detail: 1 });
    }
    
    // Expand first level by default
    document.querySelectorAll('.win-tree-item[data-level="0"].has-children').forEach(item => {
        item.classList.add('expanded');
        const icon = item.querySelector('.win-tree-toggle i');
        if (icon) {
            icon.classList.remove('fa-caret-right');
            icon.classList.add('fa-caret-down');
        }
    });
});
</script>