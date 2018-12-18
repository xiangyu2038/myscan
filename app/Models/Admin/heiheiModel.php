<?php

namespace App\Models\Admin;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Database\Query\Builder as QueryBuilder;
class heiheiModel extends Builder
{
    /**
     * Set the relationships that should be eager loaded.
     *
     * @param  mixed  $relations
     * @return $this
     */
    public function with($relations)
    {
     $eagerLoad = $this->parseWithRelations($relations);

        $this->eagerLoad = array_merge($this->eagerLoad, $eagerLoad);

        return $this;
    }
  ///解析关系

    protected function parseWithRelations(array $relations)
    {

        $results = [];
        foreach ($relations as $name => $constraints) {
            if (is_numeric($name)) {
                $name = $constraints;

                list($name, $constraints) = Str::contains($name, ':')
                    ? $this->createSelectWithConstraint($name)
                    : [$name, function () {
                        //
                    }];
            }

            // We need to separate out any nested includes. Which allows the developers
            // to load deep relationships using "dots" without stating each level of
            // the relationship with its own key in the array of eager load names.
            $results = $this->addNestedWiths($name, $results);

            $results[$name] = $constraints;
        }

        return $results;
    }

}
