<div class="form-group row align-items-center" :class="{'has-danger': errors.has('ip'), 'has-success': fields.ip && fields.ip.valid }">
    <label for="ip" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.device.columns.ip') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.ip" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('ip'), 'form-control-success': fields.ip && fields.ip.valid}" id="ip" name="ip" placeholder="{{ trans('admin.device.columns.ip') }}">
        <div v-if="errors.has('ip')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('ip') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('uuid'), 'has-success': fields.uuid && fields.uuid.valid }">
    <label for="uuid" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.device.columns.uuid') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.uuid" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('uuid'), 'form-control-success': fields.uuid && fields.uuid.valid}" id="uuid" name="uuid" placeholder="{{ trans('admin.device.columns.uuid') }}">
        <div v-if="errors.has('uuid')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('uuid') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('os_name'), 'has-success': fields.os_name && fields.os_name.valid }">
    <label for="os_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.device.columns.os_name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.os_name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('os_name'), 'form-control-success': fields.os_name && fields.os_name.valid}" id="os_name" name="os_name" placeholder="{{ trans('admin.device.columns.os_name') }}">
        <div v-if="errors.has('os_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('os_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('os_version'), 'has-success': fields.os_version && fields.os_version.valid }">
    <label for="os_version" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.device.columns.os_version') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.os_version" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('os_version'), 'form-control-success': fields.os_version && fields.os_version.valid}" id="os_version" name="os_version" placeholder="{{ trans('admin.device.columns.os_version') }}">
        <div v-if="errors.has('os_version')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('os_version') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('browser_name'), 'has-success': fields.browser_name && fields.browser_name.valid }">
    <label for="browser_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.device.columns.browser_name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.browser_name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('browser_name'), 'form-control-success': fields.browser_name && fields.browser_name.valid}" id="browser_name" name="browser_name" placeholder="{{ trans('admin.device.columns.browser_name') }}">
        <div v-if="errors.has('browser_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('browser_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('browser_version'), 'has-success': fields.browser_version && fields.browser_version.valid }">
    <label for="browser_version" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.device.columns.browser_version') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.browser_version" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('browser_version'), 'form-control-success': fields.browser_version && fields.browser_version.valid}" id="browser_version" name="browser_version" placeholder="{{ trans('admin.device.columns.browser_version') }}">
        <div v-if="errors.has('browser_version')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('browser_version') }}</div>
    </div>
</div>


