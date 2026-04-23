const { layoutProcess } = require('bpmn-auto-layout');
const fs = require('fs');

(async () => {
    try {
        console.log("Reading XML...");
        const xml = fs.readFileSync('docs/BPMN_AsIs_SaditaDecoration_V2.xml', 'utf-8');
        
        console.log("Applying auto-layout...");
        const layoutedXml = await layoutProcess(xml);
        
        fs.writeFileSync('docs/BPMN_AsIs_LayedOut.bpmn', layoutedXml);
        console.log("Success! Saved to docs/BPMN_AsIs_LayedOut.bpmn");
    } catch (e) {
        console.error("Error formatting BPMN:", e);
    }
})();
