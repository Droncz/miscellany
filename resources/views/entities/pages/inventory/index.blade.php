<?php /** @var \App\Models\Entity $entity
 * @var \App\Models\Inventory $item */?>
@extends('layouts.' . ($ajax ? 'ajax' : 'app'), [
    'title' => trans('entities/inventories.show.title', ['name' => $entity->name]),
    'description' => '',
    'breadcrumbs' => [
        ['url' => $entity->url('index'), 'label' => trans($entity->pluralType() . '.index.title')],
        ['url' => $entity->url('show'), 'label' => $entity->name],
        __('crud.tabs.inventory')
    ],
    'mainTitle' => false,
    'miscModel' => $entity->child,
    'bodyClass' => 'entity-inventory'
])
@inject('campaign', 'App\Services\CampaignService')



@section('entity-header-actions')
    @can('inventory', $entity->child)
        <div class="header-buttons">
            <a href="{{ route('entities.inventories.create', ['entity' => $entity]) }}" class="btn btn-warning btn-sm"
               data-toggle="ajax-modal" data-target="#entity-modal"
               data-url="{{ route('entities.inventories.create', ['entity' => $entity]) }}"
            >
                <i class="fa fa-plus"></i> <span class="visible-lg-inline">{{ __('entities/inventories.actions.add') }}</span>
            </a>
        </div>
    @endcan
@endsection

@include('entities.components.header', ['model' => $entity->child, 'entity' => $entity])

@section('content')
    @include('partials.errors')
    <div class="row entity-grid">
        <div class="col-md-2 entity-sidebar-submenu">
            @include($entity->pluralType() . '._menu', ['active' => 'inventory', 'model' => $entity->child, 'name' => $entity->pluralType()])
        </div>
        <div class="col-md-10 entity-main-block">
            <div class="box box-solid box-entity-inventory">
                <div class="box-body">
                    <h2 class="page-header with-border">
                        {{ trans('crud.tabs.inventory') }}
                    </h2>

                    <p class="help-block">{{ __('entities/inventories.show.helper') }}</p>

                    <table class="table table-hover table-entity-inventory">
                        <thead>
                        <tr>
                            <th>{{ __('entities/inventories.fields.is_equipped') }}</th>
                            <th>{{ __('crud.fields.item') }}</th>
                            <th>{{ __('entities/inventories.fields.amount') }}</th>
                            @if (Auth::check())
                            <th>{{ __('crud.fields.visibility') }}</th>
                            <th></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        <?php $previousPosition = null; ?>
                        @foreach ($inventory as $item)
                            @if(!empty($item->item_id) && empty($item->item))
                                @continue
                            @endif
                            @if ($previousPosition != $item->position)
                                <tr class="active cursor" data-toggle="collapse" data-target=".inventory-group-{{ \Illuminate\Support\Str::kebab($item->position) }}">
                                    <td colspan="@if(Auth::check())5 @else 4 @endif" class="text-muted">
                                        {!! $item->position ?: '<i>' . __('entities/inventories.show.unsorted') . '</i>' !!}
                                    </td>
                                </tr>
                                <?php $previousPosition = $item->position; ?>
                            @endif
                            <tr class="collapse inventory-group-{{ \Illuminate\Support\Str::kebab($item->position) }} in">
                                <td style="width: 50px">
                                    @if($item->is_equipped)
                                        <i class="fas fa-check" title="{{ __('entities/inventories.fields.is_equipped') }}"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($item->item)
                                    {!! $item->item->tooltipedLink($item->name) !!}
                                    @else
                                    {!! $item->name !!}
                                    @endif<br />
                                        <small class="text-muted">{{ $item->description }}</small>
                                </td>
                                <td>
                                    {{ $item->amount }}
                                </td>
                                @if (Auth::check())
                                    <td>
                                        @include('cruds.partials.visibility', ['model' => $item])
                                    </td>
                                    @can('inventory', $entity->child)
                                    <td class="text-right">
                                        <a href="{{ route('entities.inventories.edit', ['entity' => $entity, 'inventory' => $item->id]) }}"
                                           data-toggle="ajax-modal" data-target="#entity-modal"
                                           data-url="{{ route('entities.inventories.edit', ['entity' => $entity, 'inventory' => $item->id]) }}"
                                           title="{{ __('crud.edit') }}" class="btn btn-primary btn-xs">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <button class="btn btn-xs btn-danger delete-confirm" data-toggle="modal" data-name="{{ $item->itemName() }}"
                                                data-target="#delete-confirm" data-delete-target="delete-form-{{ $item->id }}" title="{{ __('crud.remove') }}">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['entities.inventories.destroy', 'entity' => $entity, 'inventory' => $item], 'style' => 'display:inline', 'id' => 'delete-form-' . $item->id]) !!}
                                        {!! Form::close() !!}
                                    </td>
                                    @endcan
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
