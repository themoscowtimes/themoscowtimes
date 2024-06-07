<?php

$attributes = function($attributes = [], $class = null)
{
	if(!is_array($attributes)){
		$attributes = [];
	}

	if(isset($attributes['class']) && $class !== null){
		$attributes['class'] .= ' '.$class;
	} elseif($class !== null){
		$attributes['class'] = $class;
	}

	$out = '';

	if(is_array($attributes)){
		foreach($attributes as $key => $value){
			$out .= ' '.$key.'="'.  $value.'"';
		}
	}
	return $out;
};



$assoc = function($array)
{
	$keys = array_keys($array);
	return array_keys($keys) !== $keys;
};



if($element->type === 'hidden'){
	echo '<input type="hidden" name="' . fetch::attr($element->name) . '" value="' . fetch::attr($element->value) . '" ' . $attributes($element->attributes) . ' />';
} else {
	echo '<div class="input-wrapper' . ($element->required ? ' required' : '') . ' " y-name="input-wrapper">';


	if($element->label === false) {
		$label = false;
	} elseif($element->label === '') {
		$label = '&nbsp;';
	} elseif($element->label) {
		 $label = $element->label;
	} else {
		$label = fetch::lang('field.' . $element->key);
	}


	if($label && $element->type !== 'toggle' && $element->type !== 'submit'){
		echo '<label class="form__label">' . $label;
		if($element->required) {
			echo '&nbsp;&nbsp;<em>(' . fetch::lang('required') . ')</em>';
		} elseif($element->optional !== false) {
			echo '&nbsp;&nbsp;<em>(' . fetch::lang('optional') . ')</em>';
		}
		echo '</label>';
	} else {
		echo '<label></label>';
	}

	switch($element->type){
		case 'radio':
		case 'checkbox':
		case 'select':
			if(!is_array($element->options)){
				$element->options = [];
			} elseif(!$assoc($element->options)){
				$options = [];
				foreach($element->options as $value){
					$options[$value] = fetch::lang('option.'.$element->key.'.'.$value);
				}
				$element->options = $options;
			}

			if($element->type === 'radio'){
				$html = '<div class="radio">';
				foreach($element->options as $value => $label){
					$checked = $value == $element->value ? ' checked = "checked"' : '';

					$part = '<input type="radio" name="'.$element->name.'"'.$checked.' y-name="input-'. fetch::attr($element->key) .'" value="'.$value.'"> '.$label;
					if($element->inline) {
						$html .= '<label y-name="radio-label" class="inline form__label">'.$part.'</label>';
					} else {
						$html .= '<label y-name="radio-label" class="form__label">'.$part.'</label>';
					}
				}
				$html .= '</div>';
			}

			if($element->type === 'checkbox'){

				$html = '<div class="checkbox">';
				foreach($element->options as $value => $label){
					$checked = is_array($element->value) && in_array($value, $element->value) ? ' checked = "checked"' : '';

					$part = '<input type="checkbox" name="' . fetch::attr($element->name) . '" ' . $checked . ' y-name="input-'. fetch::attr($element->key) . '" value="' . fetch::attr($value).'"> ' . $label;
					if($element->inline) {
						$html .= '<label class="inline form__label">' . $part . '</label>';
					} else {
						$html .= '<div><label class="form__label">' . $part . '</label></div>';
					}
				}
				$html .= '</div>';
			}

			if($element->type === 'select'){
				$attrs = $element->attributes;
				if($element->multiple){
					$attrs['multiple'] = 'multiple';
				}
				$html = '<select name="' . fetch::attr($element->name) . '" ' . $attributes($attrs) . ' y-name="input-' . fetch::attr($element->key) . '">';
				foreach($element->options as $value => $label){
					if($element->multiple){
						$selected = is_array($element->value) && in_array($value, $element->value) ? ' selected = "selected"' : '';
					} else {
						$selected = $value == $element->value ? ' selected = "selected"' : '';
					}
					$html .= '<option value="' . fetch::attr($value) . '"'.$selected.'> ' . fetch::text($label) . '</option>';
				}
				$html .= '</select>';
			}
			break;
		case 'submit':
		case 'button':
			$label = isset($element->label) ? $element->label : fetch::lang('field.'.$element->key);

			if($element->type === 'submit'){
				$html = '<input type="submit" value="' . fetch::attr($label) . '" ' . $attributes($element->attributes, '') . '/>';
			}
			if($element->type === 'button'){
				$html = '<button name="' . fetch::attr($element->name) . '" ' . $attributes($element->attributes, '').'>' . fetch::text($label) . '</button>';
			}
			break;
		case 'file':
			$html = '<input type="file" name="' . fetch::attr($element->name) . '" '.$attributes($element->attributes, '') . ' y-name="input-'. fetch::attr($element->key) . '" />';
			break;
		case 'image':
			$html = '<input type="image" value="' . fetch::attr($label) . '" ' . $attributes($element->attributes, '') . ' y-name="input-' . fetch::attr($element->key) . '" />';
			break;
		case 'text':
			$html = '<input type="text" name="' . fetch::attr($element->name) . '" y-name="input-' . fetch::attr($element->key) . '" value="' . fetch::attr($element->value) . '" placeholder="' . fetch::attr($label) . '" ' . $attributes($element->attributes, 'form-control') . ' ' . fetch::attr($element->required ? 'required' : '') . '/>';
			break;
		case 'email':
			$html = '<input type="email" name="' . fetch::attr($element->name) . '" y-name="input-' . fetch::attr($element->key) . '" value="' . fetch::attr($element->value) . '" placeholder="' . fetch::attr($label) . '" ' . $attributes($element->attributes, 'form-control') . ' ' . fetch::attr($element->required ? 'required' : '') . '/>';
			break;
		case 'password':
			$html = '<input type="password" name="' . fetch::attr($element->name) . '" y-name="input-' . fetch::attr($element->key) . '" value="' . fetch::attr($element->value) . '" ' . $attributes($element->attributes, 'form-control') . ' />';
			break;
		case 'textarea':
			$html = '<textarea name="' . fetch::attr($element->name) . '" y-name="input-' . fetch::attr($element->key) . '" ' . $attributes($element->attributes, '') . '>' . $element->value . '</textarea>';
			break;
		default:
			$html = fetch::file('form/element/'.$element->type, [
				'element' => $element, 'data' => isset($data) ? $data : []
			]);
	}

	echo $html;
	echo '</div>';
}
?>