<?

class Form
{
	private $obj;

	function __construct( $obj = NULL ) {
		$this->obj = $obj;
	}

	private function get( $name ) {
		if (isset($this->obj) && isset($this->obj->{$name}))
			return $this->obj->{$name};
		return '';
	}

	private function required( $required ) {
		return $required ? ' required' : '';
	}

	private function disabled( $disabled ) {
		return $disabled ? ' disabled' : '';
	}

	public function input( $type, $name, $label, $required = FALSE, $disabled = FALSE ) {
		?>
		<div class="form-group">
			<label for="<?= $name ?>"><?= $label ?></label>
			<input type="<?= $type ?>" name="<?= $name ?>" id="<?= $name ?>" value="<?= $this->get($name) ?>"<?= $this->required($required) ?><?= $this->disabled($disabled) ?> class="form-control">
		</div>
		<?
	}

	public function text( $name, $label, $required = FALSE, $disabled = FALSE ) {
		$this->input('text', $name, $label, $required, $disabled);
	}

	public function number( $name, $label, $required = FALSE, $disabled = FALSE ) {
		$this->input('number', $name, $label, $required, $disabled);
	}

	public function email( $name, $label, $required = FALSE, $disabled = FALSE ) {
		$this->input('email', $name, $label, $required, $disabled);
	}

	public function password( $name, $label, $required = FALSE, $disabled = FALSE ) {
		$this->input('password', $name, $label, $required, $disabled);
	}

	public function textarea( $name, $label ) {
		?>
		<div class="form-group">
			<label for="<?= $name ?>"><?= $label ?></label>
			<textarea name="<?= $name ?>" id="<?= $name ?>" class="form-control"><?= $this->get($name) ?></textarea>
		</div>
		<?
	}

	public function ckeditor( $name ) {
		?>
		<textarea name="<?= $name ?>"><?= $this->get($name) ?></textarea>
		<script>
			CKEDITOR.replace('<?= $name ?>', { customConfig: '/js/ckeditor_config.js' });
		</script>
		<?
	}

	public function title() {
		$this->text('title', 'Tiêu đề:', TRUE);
	}

	public function eTitle() {
		$this->text('eTitle', 'Tiêu đề: <code>English</code>', TRUE);
	}

	public function sum() {
		$this->textarea('sum', 'Tóm tắt:', TRUE);
	}

	public function eSum() {
		$this->textarea('eSum', 'Tóm tắt: <code>English</code>', TRUE);
	}

	public function desc() {
		$this->text('desc', 'Mô tả: <code>SEO</code>');
	}

	public function eDesc() {
		$this->text('eDesc', 'Mô tả: <code>English</code>');
	}

	public function keyword() {
		$this->text('keyword', 'Từ khóa: <code>SEO</code>');
	}

	public function eKeyword() {
		$this->text('eKeyword', 'Từ khóa: <code>English</code>');
	}

	public function content() {
		$this->ckeditor('content');
	}

	public function eContent() {
		$this->ckeditor('eContent');
	}

	public function lnk() {
		$this->text('lnk', "Liên kết:");
	}

	public function ind() {
		$this->number('ind', "Thứ tự hiển thị:");
	}

	public function active() {
		$inactive = isset($this->active) && !$this->active;
		?>
    <div class="form-group">
        <? uiswitch('active', !$inactive) ?>
        <label style="vertical-align: 10px; margin-left: 5px;">Hiển thị</label>
    </div>
    <?
	}

	public function img( $width, $height ) {
		?>
    <div class="form-group">
      <label>Hình ảnh <code><?= $width ?> &times; <?= $height ?></code></label>
      <? image_input($this->get('img'), $width, $height) ?>
    </div>
    <?
	}

	public function pwd() {
		?>
		<div class="form-group">
			<label for="pwd">Mật khẩu:</label>
			<input type="<?= $type ?>" name="pwd" id="pwd"<?= $this->required($required) ?><?= $this->disabled($disabled) ?> class="form-control">
		</div>
		<?
	}

	public function select( $name, $label, $options, $required = FALSE, $disabled = FALSE) {
		?>
		<div class="form-group">
			<label for="<?= $name ?>"><?= $label ?></label>
			<select name="<?= $name ?>" id="<?= $name ?>"<?= $this->required($required) ?><?= $this->disabled($disabled) ?> class="form-control">
				<? foreach ($options as $value => $text) { ?>
					<option value="<?= $value ?>"<?= selected($this->get($name)==$value) ?>><?= $text ?></option>
				<? } ?>
			</select>
		</div>
		<?
	}
}
