<?php
// Adiciona a página administrativa do plugin
function icp_add_admin_page() {
    add_menu_page(
        'Configurações de Recorte de Imagem', // Título da página
        'Recorte de Imagem', // Nome no menu
        'manage_options', // Permissão necessária
        'icp-settings', // Slug da página
        'icp_render_admin_page', // Função de callback para renderizar a página
        'dashicons-image-crop', // Ícone do menu
        80 // Posição no menu
    );
}
add_action('admin_menu', 'icp_add_admin_page');

// Renderiza a página administrativa
function icp_render_admin_page() {
    // Verifica permissões
    if (!current_user_can('manage_options')) {
        return;
    }

    // Verifica se o formulário foi enviado
    if (isset($_POST['icp_crop_settings_submit'])) {
        check_admin_referer('icp_crop_settings_action', 'icp_crop_settings_nonce');

        // Salva as opções enviadas
        $width = intval($_POST['icp_crop_width']);
        $height = intval($_POST['icp_crop_height']);
        $auto_resize = isset($_POST['icp_auto_resize']) ? 1 : 0;

        update_option('icp_crop_width', $width);
        update_option('icp_crop_height', $height);
        update_option('icp_auto_resize', $auto_resize);

        echo '<div class="updated"><p>Configurações salvas com sucesso!</p></div>';
    }

    // Recupera os valores das opções
    $width = get_option('icp_crop_width', 800);
    $height = get_option('icp_crop_height', 600);
    $auto_resize = get_option('icp_auto_resize', 1); // Padrão: habilitado

    ?>
    <div class="wrap">
        <h1>Configurações de Recorte de Imagem</h1>
        <form method="post" action="">
            <?php wp_nonce_field('icp_crop_settings_action', 'icp_crop_settings_nonce'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="icp_crop_width">Largura do Recorte (px)</label></th>
                    <td><input type="number" id="icp_crop_width" name="icp_crop_width" value="<?php echo esc_attr($width); ?>" required /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="icp_crop_height">Altura do Recorte (px)</label></th>
                    <td><input type="number" id="icp_crop_height" name="icp_crop_height" value="<?php echo esc_attr($height); ?>" required /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="icp_auto_resize">Redimensionar automaticamente</label></th>
                    <td>
                        <input type="checkbox" id="icp_auto_resize" name="icp_auto_resize" value="1" <?php checked($auto_resize, 1); ?> />
                        <label for="icp_auto_resize">Ativar redimensionamento automático</label>
                    </td>
                </tr>
            </table>
            <?php submit_button('Salvar Configurações', 'primary', 'icp_crop_settings_submit'); ?>
        </form>
    </div>
    <?php
}
