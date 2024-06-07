<?php

namespace Image\Manager;

use Sulfur\Manager\Image\Manager as BaseManager;


class Manager extends BaseManager
{

	public function crop($zone, $id)
	{
		$entity = $this->entity($id);
		if($data = $this->request->post()) {

			$success = false;
			if(isset($data['width']) && isset($data['height']) && isset($data['x']) && isset($data['y']) ) {
				$image = $this->image->image($entity->path . $entity->file);
				$image->crop((int) $data['width'], (int) $data['height'], (int) $data['x'], (int) $data['y']);
				$this->image->delete($entity->path . $entity->file);

				// new  filename to bypass cache
				$entity->file = substr(md5(microtime()), 0, 2) . $entity->file;
				
				$this->image->save($image, $entity->path . $entity->file);

				$entity->width = $data['width'];
				$entity->height = $data['height'];
				$this->data->save($entity);

				$success = true;
			}
			return $this->payload('json', [
				'data' => [
					'success' => $success
				]
			]);
		} else {
			return $this->payload('image/crop', [
				'presets' => $this->config->image('presets'),
				'image' => $this->url->action($zone, 'image', 'serve', $id),
				'url' => $this->url->action($zone, 'image', 'crop', $id)
			]);
		}
	}
}