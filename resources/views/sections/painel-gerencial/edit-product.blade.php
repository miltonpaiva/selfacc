<div class="product-popup" id="editProductPopup">
    <div class="product-popup__overlay" onclick="closeEditProductPopup()"></div>
    <div class="product-popup__content">

        <button class="product-popup__close" onclick="closeEditProductPopup()">✕</button>

        <div class="product-popup__header">
            <h3 class="product-popup__title">✎ Editar Produto</h3>
            <p class="product-popup__subtitle">Atualize os dados do produto</p>
        </div>

        <input type="hidden" id="edit_product_id">

        <div class="product-popup__form">
            <div class="product-popup__form-group">
                <label class="product-popup__label">
                    <span class="product-popup__label-text">Nome do Produto</span>
                    <span class="product-popup__required">*</span>
                </label>
                <input type="text" id="edit_name" class="product-popup__input">
            </div>

            <div class="product-popup__form-row">
                <div class="product-popup__form-group" style="flex: 1;">
                    <label class="product-popup__label">
                        <span class="product-popup__label-text">Preço (R$)</span>
                        <span class="product-popup__required">*</span>
                    </label>
                    <input type="number" id="edit_price" class="product-popup__input" step="0.01">
                </div>

                <div class="product-popup__form-group" style="flex: 1;">
                    <label class="product-popup__label">
                        <span class="product-popup__label-text">Categoria</span>
                        <span class="product-popup__required">*</span>
                    </label>
                    <select id="edit_category" class="product-popup__input">
                        @foreach($categories as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="product-popup__form-group">
                <label class="product-popup__label">
                    <span class="product-popup__label-text">Descrição</span>
                </label>
                <textarea id="edit_description" class="product-popup__textarea"></textarea>
                <small class="product-popup__help-text">Este campo é opcional</small>
            </div>

            <div class="product-popup__form-group">
                <label class="product-popup__label">
                    <span class="product-popup__label-text">Atualizar Imagem</span>
                </label>
                <div class="product-popup__file-input">
                    <input type="file" id="edit_image" class="product-popup__file-input-field" accept="image/*">
                    <div class="product-popup__file-input-label">
                        <span class="product-popup__file-icon">🖼️</span>
                        <span class="product-popup__file-text">Clique para alterar a imagem</span>
                        <small>PNG, JPG, JPEG (máx. 2MB)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-popup__footer">
            <button class="product-popup__btn product-popup__btn--secondary" onclick="closeEditProductPopup()">
                Cancelar
            </button>
            <button class="product-popup__btn product-popup__btn--primary" onclick="updateProduct()">
                ✓ Salvar Alterações
            </button>
        </div>

    </div>
</div>

<!-- POPUP NOVO PRODUTO -->
<div class="product-popup" id="newProductPopup">
    <div class="product-popup__overlay" onclick="closeNewProductPopup()"></div>
    <div class="product-popup__content">

        <button class="product-popup__close" onclick="closeNewProductPopup()">✕</button>

        <div class="product-popup__header">
            <h3 class="product-popup__title">➕ Novo Produto</h3>
            <p class="product-popup__subtitle">Preencha os dados para criar um novo produto</p>
        </div>

        <div class="product-popup__form">
            <div class="product-popup__form-group">
                <label class="product-popup__label">
                    <span class="product-popup__label-text">Nome do Produto</span>
                    <span class="product-popup__required">*</span>
                </label>
                <input type="text" id="new_name" class="product-popup__input" placeholder="Ex: Água mineral 500ml">
            </div>

            <div class="product-popup__form-row">
                <div class="product-popup__form-group" style="flex: 1;">
                    <label class="product-popup__label">
                        <span class="product-popup__label-text">Preço (R$)</span>
                        <span class="product-popup__required">*</span>
                    </label>
                    <input type="number" id="new_price" class="product-popup__input" step="0.01" placeholder="0.00">
                </div>

                <div class="product-popup__form-group" style="flex: 1;">
                    <label class="product-popup__label">
                        <span class="product-popup__label-text">Categoria</span>
                        <span class="product-popup__required">*</span>
                    </label>
                    <select id="new_category" class="product-popup__input">
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="product-popup__form-group">
                <label class="product-popup__label">
                    <span class="product-popup__label-text">Descrição</span>
                </label>
                <textarea id="new_description" class="product-popup__textarea" placeholder="Descreva características, ingredientes, etc..."></textarea>
                <small class="product-popup__help-text">Este campo é opcional</small>
            </div>

            <div class="product-popup__form-group">
                <label class="product-popup__label">
                    <span class="product-popup__label-text">Imagem do Produto</span>
                </label>
                <div class="product-popup__file-input">
                    <input type="file" id="new_image" class="product-popup__file-input-field" accept="image/*">
                    <div class="product-popup__file-input-label">
                        <span class="product-popup__file-icon">🖼️</span>
                        <span class="product-popup__file-text">Clique para selecionar uma imagem</span>
                        <small>PNG, JPG, JPEG (máx. 2MB)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-popup__footer">
            <button class="product-popup__btn product-popup__btn--secondary" onclick="closeNewProductPopup()">
                Cancelar
            </button>
            <button class="product-popup__btn product-popup__btn--primary" onclick="createProduct()">
                ✓ Criar Produto
            </button>
        </div>

    </div>
</div>

<!-- POPUP CATEGORIAS -->
<div class="product-popup" id="categoriesPopup">
    <div class="product-popup__overlay" onclick="closeCategoriesPopup()"></div>
    <div class="product-popup__content">

        <button class="product-popup__close" onclick="closeCategoriesPopup()">✕</button>

        <div class="product-popup__header">
            <h3 class="product-popup__title">📂 Gerenciar Categorias</h3>
            <p class="product-popup__subtitle">Crie, edite ou remova categorias de produtos</p>
        </div>

        <div class="product-popup__form">
            <div class="product-popup__form-group">
                <label class="product-popup__label">
                    <span class="product-popup__label-text">Adicionar Nova Categoria</span>
                </label>
                <div class="product-popup__input-group">
                    <input type="text" id="new_category_name" class="product-popup__input" placeholder="Ex: Bebidas, Alimentos, Acessórios..." style="flex: 1;">
                    <button class="product-popup__btn product-popup__btn--primary" onclick="addNewCategory()" style="flex-shrink: 0;">
                        ➕ Adicionar
                    </button>
                </div>
            </div>
        </div>

        <div class="product-popup__divider"></div>

        <div class="product-popup__categories-section">
            <h4 class="product-popup__section-title">📋 Categorias Existentes</h4>
            <div id="categoriesList" class="product-popup__categories-list">
                <!-- Lista de categorias será carregada aqui via JavaScript -->
            </div>
        </div>

    </div>
</div>
