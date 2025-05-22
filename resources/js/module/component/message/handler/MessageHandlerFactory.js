import BroadacstHandler from "./BroadacstHandler.js";
import GreetingHandler from "./GreetingHandler.js";
import TemplateHandler from "./TemplateHandler.js";

export default class MessageHandlerFactory{
      static getHandler(type, sendingService, isUpdate){
            switch(type){
                  case "broadcast" : return new BroadacstHandler(sendingService)
                  case "greeting" : return new GreetingHandler(sendingService)
                  case "template" : return new TemplateHandler(sendingService, isUpdate)
                  default: throw new Error(`Unknown message type: ${type}`);
            }
      }
}