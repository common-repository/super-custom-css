(function(wp){
    var addFilter=wp.hooks.addFilter,createHigherOrderComponent=wp.compose.createHigherOrderComponent,Fragment=wp.element.Fragment,InspectorControls=wp.blockEditor.InspectorControls,PanelBody=wp.components.PanelBody,__=wp.i18n.__,el=wp.element.createElement,Component=wp.element.Component;
    class CodeMirrorCSS extends Component{
    constructor(props){super(props);this.state={editor:null};this.textareaRef=wp.element.createRef()}
    componentDidMount(){const editor=wp.CodeMirror.fromTextArea(this.textareaRef.current,{lineNumbers:true,mode:'css',theme:'default',extraKeys:{"Ctrl-Space":"autocomplete"},matchBrackets:true,autoCloseBrackets:true,viewportMargin:Infinity});
    editor.on('change',()=>{this.props.onChange(editor.getValue())});
    this.setState({editor});
    setTimeout(()=>{editor.refresh()},100)}
    componentDidUpdate(prevProps){if(this.props.value!==prevProps.value&&this.state.editor){if(this.state.editor.getValue()!==this.props.value){this.state.editor.setValue(this.props.value)}}}
    render(){return el('div',{className:'super-block-css-editor'},el('textarea',{ref:this.textareaRef,defaultValue:this.props.value}))}}
    function addCustomCSSAttribute(settings){
    settings.attributes=Object.assign(settings.attributes,{customCSS:{type:'string',default:''},customCSSId:{type:'string',default:''}});
    return settings}
    addFilter('blocks.registerBlockType','super-block-css/custom-css-attribute',addCustomCSSAttribute);
    var withCustomCSS=createHigherOrderComponent(function(BlockEdit){
    return class extends Component{
    componentDidMount(){this.updateCSSId()}
    componentDidUpdate(prevProps){if(this.props.attributes.customCSS!==prevProps.attributes.customCSS){this.updateCSSId()}}
    updateCSSId(){const{attributes,setAttributes}=this.props,{customCSS,customCSSId}=attributes;
    if(customCSS&&!customCSSId){setAttributes({customCSSId:'super-block-css-'+Math.random().toString(36).substr(2,9)})}}
    render(){const{attributes,setAttributes}=this.props,{customCSS,customCSSId}=attributes;
    return el(Fragment,null,
    el(BlockEdit,this.props),
    el(InspectorControls,null,
    el(PanelBody,{title:__('Custom CSS','super-block-css'),initialOpen:false},
    el('p',{className:'super-block-css-label'},__('Add your custom CSS.','super-block-css')),
    el(CodeMirrorCSS,{value:customCSS,onChange:(value)=>setAttributes({customCSS:value})}),
    el('p',{className:'super-block-css-help'},__('You do not need to use a selector to apply CSS. Simply add the CSS styles directly.','super-block-css')),
    el('div',{className:'super-block-css-example'},
    el('h4',{},__('Example:','super-block-css')),
    el('pre',{},"    background: #eee;\n    text-align: center;\n")))))}}
    },'withCustomCSS');
    addFilter('editor.BlockEdit','super-block-css/with-custom-css',withCustomCSS);
    var withCustomCSSPreview=createHigherOrderComponent(function(BlockListBlock){
    return function(props){
    var attributes=props.attributes,customCSS=attributes.customCSS,customCSSId=attributes.customCSSId;
    if(customCSS&&customCSSId){
    return el(Fragment,null,
    el(BlockListBlock,Object.assign({},props,{className:(props.className||'')+' '+customCSSId})),
    el('style',null,'.'+customCSSId+' { '+customCSS+' }'))}
    return el(BlockListBlock,props)}},'withCustomCSSPreview');
    addFilter('editor.BlockListBlock','super-block-css/with-custom-css-preview',withCustomCSSPreview);
    })(window.wp);