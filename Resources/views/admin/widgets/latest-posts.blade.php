<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">{{ trans('blog::doc.latest docs') }}</h3>
    </div><!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-striped">
            <tbody><tr>
                <th style="width: 10px">#</th>
                <th>{{ trans('blog::doc.table.title') }}</th>
                <th>{{ trans('core::core.table.created at') }}</th>
            </tr>
            <?php if (isset($docs)): ?>
                <?php foreach ($docs as $doc): ?>
                    <tr>
                        <td>{{ $doc->id }}</td>
                        <td>{{ $doc->title }}</td>
                        <td>{{ $doc->created_at }}</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div>
