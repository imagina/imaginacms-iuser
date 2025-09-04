<?php

namespace Modules\Iuser\Transformers;

use Imagina\Icore\Transformers\CoreResource;

class UserTransformer extends CoreResource
{
  /**
   * Attribute to exclude relations from transformed data
   * @var array
   */
  protected array $excludeRelations = ['fields'];

  /**
   * Method to merge values with response
   *
   * @return array
   */
  public function modelAttributes($request): array
  {
    $attributes = [
      'files' => $this->whenLoaded('files', fn() => $this->files->byZones($this->mediaFillable, $this)),
      'fields' => $this->whenLoaded('fields', fn() => $this->fields->mappedFields()),
    ];

    return $attributes;
  }
}
