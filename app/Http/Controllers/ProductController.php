<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SimpleValues as SV;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * upInsertProduct - Cria ou atualiza um produto
     */
    public function upInsertProduct(Request $request): object
    {
        $is_update = $request->has('id') && !empty($request->get('id'));
        return self::newOrUpdateModel($request, new Product(), null, $is_update);
    }

    /**
     * store - Cria um novo produto com suporte a upload de imagem
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $createData = [
                'p_name' => $request->input('p_name') ?? $request->input('name'),
                'p_price' => $request->input('p_price') ?? $request->input('price'),
                'p_description' => $request->input('p_description') ?? $request->input('description') ?? '',
                'p_sv_category_pd_fk' => $request->input('p_sv_category_pd_fk') ?? $request->input('category_id'),
            ];

            // Validar se os dados obrigatórios estão presentes
            if (!$createData['p_name'] || !$createData['p_price'] || !$createData['p_sv_category_pd_fk']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nome, Preço e Categoria são obrigatórios',
                    'debug' => $request->all()
                ], 400);
            }

            // Processa upload de imagem se enviado
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Valida extensão da imagem
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $extension = strtolower($image->getClientOriginalExtension());
                
                if (!in_array($extension, $allowed_extensions)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Formato de imagem não permitido. Use: ' . implode(', ', $allowed_extensions)
                    ], 400);
                }

                // Salva nova imagem
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $path = $image->storeAs('products', $filename, 'public');
                $createData['p_image'] = $path;
            }

            $product = Product::create($createData);

            return response()->json([
                'success' => true,
                'message' => 'Produto criado com sucesso',
                'product' => $product
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar produto: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * show - Obtém um produto específico
     */
    public function show($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * update - Atualiza um produto com suporte a upload de imagem
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            // Debug: verificar o que está chegando
            \Log::info('Update request data:', $request->all());

            $updateData = [
                'p_name' => $request->input('name') ?? $request->get('name'),
                'p_price' => $request->input('price') ?? $request->get('price'),
                'p_description' => $request->input('description') ?? $request->get('description') ?? '',
                'p_sv_category_pd_fk' => $request->input('category_id') ?? $request->get('category_id'),
            ];

            // Validar se os dados obrigatórios estão presentes
            if (!$updateData['p_name'] || !$updateData['p_price'] || !$updateData['p_sv_category_pd_fk']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nome, Preço e Categoria são obrigatórios',
                    'debug' => $request->all()
                ], 400);
            }

            // Processa upload de imagem se enviado
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Valida extensão da imagem
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $extension = strtolower($image->getClientOriginalExtension());
                
                if (!in_array($extension, $allowed_extensions)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Formato de imagem não permitido. Use: ' . implode(', ', $allowed_extensions)
                    ], 400);
                }

                // Remove imagem antiga se existir
                if ($product->p_image && Storage::disk('public')->exists($product->p_image)) {
                    Storage::disk('public')->delete($product->p_image);
                }

                // Salva nova imagem
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $path = $image->storeAs('products', $filename, 'public');
                $updateData['p_image'] = $path;
            }

            $product->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Produto atualizado com sucesso',
                'product' => $product
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar produto: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * getCategories - Lista todas as categorias de produtos
     */
    public function getCategories(): JsonResponse
    {
        try {
            $categories = SV::where('sv_group', 'category_pd')->get()->map(function ($cat) {
                return [
                    'id' => $cat->sv_id,
                    'title' => $cat->sv_title,
                ];
            });

            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar categorias: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * createCategory - Cria uma nova categoria
     */
    public function createCategory(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:simple_values,sv_title,NULL,sv_id,sv_group,category_pd',
            ]);

            $category = SV::create([
                'sv_title' => $validated['title'],
                'sv_group' => 'category_pd',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Categoria criada com sucesso',
                'category' => [
                    'id' => $category->sv_id,
                    'title' => $category->sv_title,
                ]
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar categoria: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * updateCategory - Atualiza uma categoria existente
     */
    public function updateCategory(Request $request, $id): JsonResponse
    {
        try {
            $category = SV::where('sv_id', $id)->where('sv_group', 'category_pd')->firstOrFail();

            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:simple_values,sv_title,' . $id . ',sv_id,sv_group,category_pd',
            ]);

            $category->update([
                'sv_title' => $validated['title'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Categoria atualizada com sucesso',
                'category' => [
                    'id' => $category->sv_id,
                    'title' => $category->sv_title,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar categoria: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * deleteCategory - Deleta uma categoria
     */
    public function deleteCategory($id): JsonResponse
    {
        try {
            $category = SV::where('sv_id', $id)->where('sv_group', 'category_pd')->firstOrFail();

            // Verifica se existe algum produto usando esta categoria
            $productsCount = Product::where('p_sv_category_pd_fk', $id)->count();
            if ($productsCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível deletar esta categoria. Há ' . $productsCount . ' produto(s) usando-a.'
                ], 400);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Categoria deletada com sucesso'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar categoria: ' . $th->getMessage()
            ], 500);
        }
    }
}
